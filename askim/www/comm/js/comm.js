document.addEventListener("DOMContentLoaded", function () {
    fetch('/comm/carousel-data.json')
        .then(response => response.json())
        .then(data => {
            let script = document.createElement('script');
            script.type = 'application/ld+json';
            script.textContent = JSON.stringify(data);
            document.head.appendChild(script);
        })
        .catch(error => console.error('JSON 데이터를 불러오는 중 오류 발생:', error));
});
