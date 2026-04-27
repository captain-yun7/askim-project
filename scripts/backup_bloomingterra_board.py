#!/usr/bin/env python3
"""
Bloomingterra DB board 테이블 백업 스크립트.
- 대상: da_board_*, da_board_manage (메타)
- 출력: _docs/db-backup/<timestamp>/<table>.sql (CREATE + INSERT 형식)
- 복원: mysql < <table>.sql 또는 동일 SQL을 콘솔에서 실행
"""
import os
import sys
import datetime
import pymysql

CFG = dict(
    host="bloomingterra.com",
    user="gcsd33_bloomingterra",
    password="bloomingterra123",
    database="gcsd33_bloomingterra",
    charset="utf8mb4",
)

TARGETS = [
    "da_board_gallery",
    "da_board_manage",
    "da_board_global",
    "da_board_gallery_tags",
    "da_board_file",
]


def quote_value(v):
    if v is None:
        return "NULL"
    if isinstance(v, (int, float)):
        return str(v)
    if isinstance(v, (bytes, bytearray)):
        v = v.decode("utf-8", errors="replace")
    if isinstance(v, datetime.datetime):
        return f"'{v.strftime('%Y-%m-%d %H:%M:%S')}'"
    if isinstance(v, datetime.date):
        return f"'{v.strftime('%Y-%m-%d')}'"
    s = str(v).replace("\\", "\\\\").replace("'", "''")
    return f"'{s}'"


def dump_table(cur, table, out_path):
    cur.execute(f"SHOW CREATE TABLE `{table}`")
    row = cur.fetchone()
    create_sql = row[1] if row else ""

    cur.execute(f"SELECT * FROM `{table}`")
    rows = cur.fetchall()
    cols = [c[0] for c in cur.description]
    col_list = ", ".join(f"`{c}`" for c in cols)

    with open(out_path, "w", encoding="utf-8") as f:
        f.write(f"-- Backup: {table}\n")
        f.write(f"-- At: {datetime.datetime.now().isoformat()}\n")
        f.write(f"-- Rows: {len(rows)}\n\n")
        f.write(f"DROP TABLE IF EXISTS `{table}`;\n")
        f.write(create_sql + ";\n\n")
        if rows:
            f.write(f"-- Data ({len(rows)} rows)\n")
            for r in rows:
                values = ", ".join(quote_value(v) for v in r)
                f.write(f"INSERT INTO `{table}` ({col_list}) VALUES ({values});\n")
        f.write("\n")
    return len(rows)


def main():
    timestamp = datetime.datetime.now().strftime("%Y-%m-%d_%H%M%S")
    out_dir = os.path.join(
        os.path.dirname(os.path.abspath(__file__)),
        "..",
        "_docs",
        "db-backup",
        f"{timestamp}_pre-related-posts",
    )
    out_dir = os.path.realpath(out_dir)
    os.makedirs(out_dir, exist_ok=True)

    conn = pymysql.connect(**CFG)
    try:
        with conn.cursor() as cur:
            print(f"backup dir: {out_dir}\n")
            for table in TARGETS:
                try:
                    out_file = os.path.join(out_dir, f"{table}.sql")
                    n = dump_table(cur, table, out_file)
                    size = os.path.getsize(out_file)
                    print(f"  {table:32s} rows={n:5d}  size={size:8d}b  -> {os.path.basename(out_file)}")
                except Exception as e:
                    print(f"  {table:32s} FAILED: {e}")
                    return 1
    finally:
        conn.close()

    print(f"\nDONE. Restore (gallery only): mysql < {out_dir}/da_board_gallery.sql")
    return 0


if __name__ == "__main__":
    sys.exit(main())
