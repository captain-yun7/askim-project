$(document).ready(function(){
	var md_ = ()=>/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? true : false;
	gsap.registerPlugin(ScrollTrigger, ScrollSmoother, SplitText, ScrollToPlugin);

	ScrollTrigger.create({
		trigger : ".section_intro",
		endTrigger : ".section_intro",
		pin : true,
		// markers : true,
		end : "bottom bottom",
	})
/*--------------------------------------------------------------

	//section 1	

--------------------------------------------------------------*/
	const sec01DesktopScroll = ()=>{
		// split 배열 초기화
		let split = [];

		// 텍스트 분할하여 split 배열에 추가
		gsap.utils.toArray('.sec01.ver_pc .layout+.lay03 .wo3 dt .in').forEach((element, index) => {
			split.push(new SplitText(element, { type: "chars" }));
		});

		// 섹션 내용의 높이 설정
		const sec01ContentHeight = $('.sec01.ver_pc .layout').outerHeight();
		$('.sec01.ver_pc').css('height', sec01ContentHeight * 4);

		// 메인 타임라인 함수
		function mainTimeline() {
			const mainTl = gsap.timeline({
				scrollTrigger: {
					trigger: '.sec01.ver_pc',
					pin: true,
					pinSpacing: false,
					end: "bottom top",
					markers: false,
					scrub: true,
					invalidateOnRefresh: true,
				}
			})
				.set('.sec01.ver_pc .lay03 .wo3', {
					opacity: 0
				})
				.to('.sec01.ver_pc .lay01 .word', {
					top: "-100%",
				})
				.to('.sec01.ver_pc .lay02', {
					clipPath: "inset(0% 0% 0% 0%)",
					onReverseComplete: () => {
						$('#header').removeClass('white');
						$('.sec01.ver_pc .btn_scroll').removeClass('on');
					},
					onComplete: () => {
						$('#header').addClass('white');
						$('.sec01.ver_pc .btn_scroll').addClass('on');
					}
				})
				.to('.sec01.ver_pc .lay02 .word', {
					top: "-100%",
				})
				.to('.sec01.ver_pc .lay03', {
					clipPath: "inset(0% 0% 0% 0%)",
					onReverseComplete: () => {
						$('#header').addClass('white');
						$('.sec01.ver_pc .btn_scroll').addClass('on');
					},
					onComplete: () => {
						$('#header').removeClass('white');
						$('.sec01.ver_pc .btn_scroll').removeClass('on');
					}
				})
				.to('.sec01.ver_pc .lay03 .word', {
					opacity: 0
				})
				.fromTo('.sec01.ver_pc .lay03 .wo2', {
					x: () => window.innerWidth
				}, {
					x: () => -1 * document.querySelector('.sec01.ver_pc .lay03 .wo2').clientWidth,
					ease: "none",
					duration: 2,
				}, 'lb_about')
				.set('.sec01.ver_pc .lay03 .wo2', {
					left: "50%",
					xPercent: -50,
					x: 0,
					opacity: 0,
				})
				.to('.sec01.ver_pc .lay03 .wo2', {
					opacity: 1,
					scale: () => 0.125,
				})
				.to('.sec01.ver_pc .lay03 .wo2', {
					top: () => {
						let height = $('.sec01.ver_pc .lay03 .wo3 dt').height();
						let margin = gsap.getProperty('.sec01.ver_pc .lay03 .wo3 dd', 'marginTop');
						return `calc( 50% + ${(height / 2) + margin}px )`;
					},
				})
				.set('.sec01.ver_pc .lay03 .wo2', {
					onComplete: () => {
						$('.sec01.ver_pc .lay03 .wo2').css('font-size', '19.875vw');
					}
				})
				.set('.sec01.ver_pc .lay03 .wo3', {
					opacity: 1,
				});

			gsap.utils.toArray('.sec01.ver_pc .layout+.lay03 .wo3 dt .in').forEach((element, index) => {
				mainTl
					.to($(element).find('.typ'), {
						opacity: 1,
					}, index === 0 ? `m${index}` : `m${index}+=25%`)
					.fromTo(split[index].chars, {
						opacity: 0,
						visibility: "hidden",
					}, {
						duration: 0.04,
						opacity: 1,
						visibility: "visible",
						stagger: {
							each: 0.05,
							onStart() {
								let target = this.targets()[0];
								$(element).find('.typ').css('left', (target.getBoundingClientRect().left - element.getBoundingClientRect().left) + target.offsetWidth);
							},
							onReverseComplete() {
								let target = this.targets()[0];
								$(element).find('.typ').css('left', (target.getBoundingClientRect().left - element.getBoundingClientRect().left) - target.offsetWidth);
							}
						},
						onComplete: () => {
							gsap.timeline()
								.to($(element).find('.typ'), {
									opacity: 0.2,
									repeat: 2,
									yoyo: true
								})
								.to($(element).find('.typ'), {
									opacity: 0,
								});
						}
					}, ">")
			});

			mainTl.to({}, {}, "+=2");

			return mainTl;
		}

		// 메인 타임라인 실행
		const aboutClick = mainTimeline();

		// ABOUT 섹션으로 이동
		const aboutMove = () => {
			const scrollSet = aboutClick.scrollTrigger.start + (aboutClick.scrollTrigger.end - aboutClick.scrollTrigger.start) * (aboutClick.labels.lb_about / aboutClick.duration());
			gsap.to(window, { duration: 1, scrollTo: { y: scrollSet } });
		}

		// ABOUT 메뉴 클릭 시 스크롤
		$('.hd_lnb li a').click(function () {
			if ($(this).text() === "ABOUT") {
				aboutMove();
				return false;
			}
		});

		// 스크롤 버튼 클릭 시 스크롤
		$('.sec01 .btn_scroll .scroll').click(function () {
			aboutMove();
			return false;
		});

	}

	const sec01MobileScroll = ()=>{
		$('.sec01').css('height',`auto`);

		 ScrollTrigger.create({
			trigger : ".sec01",
			start : "top top",
			end : "bottom bottom",
		})
	}

	ScrollTrigger.matchMedia({
        "(min-width:861px)" : ()=>{
            if(!md_()){
                sec01DesktopScroll();
            }else{
                sec01MobileScroll();
            }
        },
        "(max-width:860px)" : ()=>{
            sec01MobileScroll();
        },
    });


/*--------------------------------------------------------------

	//section 2	

--------------------------------------------------------------*/
	const countArr = [];

    $(".sec02 .layout li .tbx .h4").each((i,e)=>{
        countArr.push($(e).attr('data-count'));
    });
   const desktopScroll = ()=>{
        // sec02 크기지정
        $('.sec02').css('height',`${100*9}vh`);

        // 텍스트 애니메이션 초기화
        gsap.set('.sec02 .layout li .tbx .cir-fi, .sec02 .layout li .tbx .h4, .sec02 .layout li .tbx .h3, .sec02 .layout li .tbx .p',{
            y : 0,
            opacity : 1,
        })

        // 첫번째 섹션 초기화
        gsap.utils.toArray('.sec02 .bu_circle').forEach(e=>{
            gsap.set(e,{
                y : 0,
                opacity : 1
            });
        })
        
	    for (let i = 1; i < 11; i++){
	        gsap.set(`.sec02 .layout .l1 .bu${String(i).padStart(2,'0')}`,{
				scaleY : 1,
            });
        }

        // 두번째 섹션 초기화
        gsap.set('.sec02 .layout .l2 .block .lob .item',{
            x : 0,
            opacity : 1
        })
        $('.sec02 .layout .l2 .block .lob .item').removeAttr('style');
        $('.sec02 .layout .l2 .block .lob .left').removeAttr('style');
        $('.sec02 .layout .l2 .block .lob .right').removeAttr('style');

        // 섹션1 애니메이션
        const s1TL = gsap.timeline({});
        s1TL.pause();
        s1TL.to('.sec02 .layout .l1 .tbx .h4',{
            y : 0,
            opacity : 1,
            onStart : ()=>{
                gsap.to('.sec02 .layout .l1 .tbx .h4 span',{
                    textContent: countArr[0],
                    duration: 1,
                    ease: "power1.in",
                    snap: { textContent: 1 },
                    stagger: {
                        each: 0.5,
                        /* onUpdate: function() {
                            this.targets()[0].innerHTML = numberWithCommas(Math.ceil(this.targets()[0].textContent));
                        }, */
                    }
                })
            },
        })
        .to('.sec02 .layout .l1 .tbx .h3',{
            y : 0,
            opacity : 1
        },">-=50%")
        .to('.sec02 .layout .l1 .tbx .p',{
            y : 0,
            opacity : 1
        },">-=50%")
		.to('.sec02 .layout .obj',{
            y : 0,
            opacity : 1
        },">-=50%")

        for (let i = 1; i < 11; i++){

            s1TL.to(`.sec02 .layout .l1 .bu${String(i).padStart(2,'0')}`,{
                scale : 1,
                transformOrigin : "center bottom"
            },">-=75%");

        }

        gsap.utils.toArray('.sec02 .bu_circle').forEach(e=>{
            s1TL.to(e,{
                y : 0,
                opacity : 1
            },">-=75%");
        })
		

          // 섹션2 애니메이션
        const s2TL = gsap.timeline({});
        
        s2TL.pause();

        s2TL.to('.sec02 .layout .l2 .tbx .cir-fi',{
            y : 0,
            opacity : 1
        });

        s2TL.to('.sec02 .layout .l2 .tbx .h4',{
            y : 0,
            opacity : 1,
            onStart : ()=>{
                gsap.to('.sec02 .layout .l2 .tbx .h4 span',{
                    textContent: countArr[1],
                    duration: 1,
                    ease: "power1.in",
                    snap: { textContent: 1 },
                    stagger: {
                        each: 0.5,
                        /* onUpdate: function() {
                            this .targets()[0].innerHTML = numberWithCommas(Math.ceil(this.targets()[0].textContent));
                        }, */
                    }
                })
            },
        },">-=50%")
        .to('.sec02 .layout .l2 .tbx .h3',{
            y : 0,
            opacity : 1
        },">-=50%")
        .to('.sec02 .layout .l2 .tbx .p',{
            y : 0,
            opacity : 1
        },">-=50%")


        .to('.sec02 .layout .l2 .block .lob .item',{
            x : 0,
            opacity : 1
        },'a')

        .to('.sec02 .layout .l2 .block .lob .left',{
            left : 0,
            opacity : 1
        },'a+=25%')

        .to('.sec02 .layout .l2 .block .lob .right',{
            right : 0,
            opacity : 1
        },'a+=50%');

        // 섹션1 초기화
        function s1TL_SET(){

            s1TL.pause();
            s1TL.seek(0);

            gsap.set('.sec02 .layout .l1 .tbx .h4',{
                y : 75,
                opacity : 0
            })

            gsap.set('.sec02 .layout .l1 .tbx .h3',{
                y : 75,
                opacity : 0
            });

            gsap.set('.sec02 .layout .l1 .tbx .p',{
                y : 75,
                opacity : 0
            })

            for (let i = 1; i < 11; i++){

                gsap.set(`.sec02 .layout .l1 .bu${String(i).padStart(2,'0')}`,{
                    scale : 0,
                    transformOrigin : "center bottom"
                });

            }

            gsap.utils.toArray('.sec02 .bu_circle').forEach(e=>{
                gsap.set(e,{
                    y : 75,
                    opacity : 0
                });
            })

            gsap.set('.sec02 .layout .l1 .tbx .h4 span',{
                textContent : 0
            });


        } s1TL_SET();

        // 섹션2 초기화
        function s2TL_SET(){

            gsap.set('.sec02 .layout .l2 .tbx .cir-fi',{
                y : 75,
                opacity : 0
            });

            gsap.set('.sec02 .layout .l2 .tbx .h4',{
                y : 75,
                opacity : 0,
            })
            gsap.set('.sec02 .layout .l2 .tbx .h3',{
                y : 75,
                opacity : 0
            })
            gsap.set('.sec02 .layout .l2 .tbx .p',{
                y : 75,
                opacity : 0
            })
			gsap.set('.sec02 .layout .obj',{
                y : 75,
                opacity : 0
            })
			
	        gsap.set('.sec02 .layout .l2 .block .lob .item',{
                x : 75,
                opacity : 0
            })

            gsap.set('.sec02 .layout .l2 .block .lob .left',{
                left : -50,
                opacity : 0
            })

            gsap.set('.sec02 .layout .l2 .block .lob .right',{
                right : 50,
                opacity : 0
            });

            gsap.set('.sec02 .layout .l2 .tbx .h4 span',{
                textContent : 0
            })


        } s2TL_SET();

   

        // 섹션애니메이션
        function InitTl(index){
            let tl;

            switch(index){
                case 0:
                    tl = s1TL;
                break;
                case 1:
                    tl = s2TL;
                break;
            }
            tl.restart();
        }

        //  기본 애니메이션 원 끝나면 실행
        function cirTl(){
            gsap.utils.toArray('.cir-fi').forEach(e2=>{

                $(e2).children().each((i,e)=>{
                    gsap.fromTo(e,{
                        xPercent : 0,
                        yPercent : 0,
                    },{
                        xPercent : 0,
                        yPercent : 0,
                        scale : ()=>{
                            let scale = 1;
                            switch(i){
                                case 0 :
                                    scale = 0.8;
                                    break;
                                case 1 :
                                    scale = 0.7;
                                    break;
                                case 2 :
                                    scale = 0.5;
                                    break;
                            }
                            return scale;
                        },
                        ease : "power1.inOut",
                        repeat : -1,
                        yoyo : true,
                        duration : ()=>{
                            let x = 0;
                            switch(i){
                                case 0 :
                                    x = 1.4;
                                    break;
                                case 1 :
                                    x = 1.2;
                                    break;
                                case 2 :
                                    x = 1;
                                    break;
                            }
                            return x;
                        }
                    })
                });

            });

        }

        cirTl();


        // 핀고정
        ScrollTrigger.create({
            trigger : ".sec02 .pin",
            endTrigger : ".sec02",
            pin : true,
            // markers : true,
            end : "bottom bottom",
        })


        // 메인 동그라미
        const mainCTL = gsap.timeline({});
        $('.sec02 .cir .line .lib').each((i,e)=>{
            mainCTL.fromTo(e,{
                xPercent : -50,
                yPercent : -50,
            },{
                xPercent : -50,
                yPercent : -50,
                scale : ()=>{
                    let scale = 1;
                    switch(i){
                        case 0 :
                            scale = 0.8;
                            break;
                        case 1 :
                            scale = 0.7;
                            break;
                        case 2 :
                            scale = 0.5;
                            break;
                    }
                    return scale;
                },
                ease : "power1.inOut",
                repeat : -1,
                yoyo : true,
                duration : ()=>{
                    let x = 0;
                    switch(i){
                        case 0 :
                            x = 1.4;
                            break;
                        case 1 :
                            x = 1.2;
                            break;
                        case 2 :
                            x = 1;
                            break;
                    }
                    return x;
                }
            },'m')
        });

        mainCTL.pause();
        

        // 메인 애니메이션
        function main(){

            //  원세팅
            gsap.utils.toArray('.sec02 .cir .line .lib').forEach(e=>{
                gsap.set(e,{
                    xPercent: -50,
                    yPercent: -50,
                })
            })

            const s3TL = gsap.timeline({
                scrollTrigger : {
                    trigger : ".sec02",
                    end : `top+=${$('.sec02').height()/4}`,
                    invalidateOnRefresh : true,
                    scrub : 1,
                    // markers : {startColor: "skyblue", endColor: "skyblue", fontSize: "12px"},
                    onLeave : ()=>{
                        mainCTL.play();
                    },
                    onEnterBack : ()=>{
                        mainCTL.pause(0);
                    }
                },
            })
            .set('.sec02 .cir .line',{
                xPercent : -50,
                yPercent : -50,
                left : "50%"
            })
            .set('.sec02 .cir .line .bar', {
                opacity : 0,
                scaleX : 0
            })
            
            gsap.utils.toArray('.sec02 .cir .line .lib').forEach((e,i)=>{
                s3TL.to(e,{
                    scale : ()=>{
                        let scale = 1;
                        switch(i){
                            case 0 :
                                scale = 0.8;
                                break;
                            case 1 :
                                scale = 0.7;
                                break;
                            case 2 :
                                scale = 0.5;
                                break;
                        }
                        return scale;
                    },
                    ease : "power1.inOut",
                    repeat : 1,
                    yoyo : true,
                    duration : ()=>{
                        let x = 0;
                        switch(i){
                            case 0 :
                                x = 1.4;
                                break;
                            case 1 :
                                x = 1.2;
                                break;
                            case 2 :
                                x = 1;
                                break;
                        }
                        return x;
                    },
                    onStart : ()=>{
                        // scroller.scrollTo(".sec02",true,"top top");
                    },
                },'ab')
            });

            s3TL.to('.sec02 .cir .line',{
                yPercent : -50,
                top : "50%",
            },'b')
            .to('.sec02 .cir .line .lib',{
                scale : 1
            },'b')
            .to('.sec02 .cir .line',{
                yPercent : 20,
                width : ()=>{
                    return $('.sec02 .layout li').eq(0).find('.cir-fi').outerWidth();
                },
            })
            .to('.sec02 .cir .line', {
                xPercent : 0,
                left : ()=>{
                    return $('.sec02 .layout li .cir-fi').offset().left
                }
            },'a')
            .to('.sec02 .cir .line .bar', {
                opacity :1,
                scaleX : 1,
                transformOrigin : "left"
            },'a-=25%')
            .to('.sec02 .cir .line .bar', {
                opacity :0,
                scaleX : 0,
            })

        }

        main();


        // 섹션별 나누기
        const panel = gsap.utils.toArray(' .sec02 .layout li');

        panel.forEach((e,i)=>{

            ScrollTrigger.create({
                trigger : '.sec02',
                start : `top+=${$('.sec02').height()/(panel.length+1) * (i+1)} top`,
                // markers : true,
                toggleClass : {targets: e, className: "on"},
                onEnter : ()=>{
                    if(i == 0){
                        s1TL_SET();
                    }else if (i == 1){
                        s2TL_SET();
                    }
                    InitTl(i);
                },
                onLeaveBack : ()=>{
                    
                    let is = i-1;
                    if(is <= 0){
                        is = 0;
                    }

                    s1TL_SET();
                    s2TL_SET();
                    InitTl(is);
                },
            })

        })
   }

   const mobileScroll = ()=>{
    // 텍스트 애니메이션 초기화
    gsap.set('.sec02 .layout li .tbx .cir-fi, .sec02 .layout li .tbx .h4, .sec02 .layout li .tbx .h3, .sec02 .layout li .tbx .p',{
        y : 0,
        opacity : 1,
    })

    // 첫번째 섹션 초기화
    gsap.utils.toArray('.sec02 .bu_circle').forEach(e=>{
        gsap.set(e,{
            y : 0,
            opacity : 1
        });
    })
    
    $('.sec02 .bu_logo').children().each((i,e)=>{
        gsap.set(e,{
            y : 0,
            opacity : 1
        });
    });

    for (let i = 1; i < 11; i++){

        gsap.set(`.sec02 .layout .l1 .bu${String(i).padStart(2,'0')}`,{
            scaleY : 1,
        });

    }


    // 두번째 섹션 초기화

    gsap.set('.sec02 .layout li:nth-of-type(2) .block .lob .item',{
        x : 0,
        opacity : 1
    })
    $('.sec02 .layout .l2 .block .lob .item').removeAttr('style');
    $('.sec02 .layout .l2 .block .lob .left').removeAttr('style');
    $('.sec02 .layout .l2 .block .lob .right').removeAttr('style');

    
    // 텍스트 애니메이션 초기화
    gsap.set('.sec02 .layout li .tbx .cir-fi, .sec02 .layout li .tbx .h4, .sec02 .layout li .tbx .h3, .sec02 .layout li .tbx .p',{
        y : 0,
        opacity : 1,
    })

    // 첫번째 섹션 초기화

    gsap.utils.toArray('.sec02 .bu_circle').forEach(e=>{
        gsap.set(e,{
            y : 0,
            opacity : 1
        });
    })
    
    $('.sec02 .bu_logo').children().each((i,e)=>{
        gsap.set(e,{
            y : 0,
            opacity : 1
        });
    });

    for (let i = 1; i < 11; i++){

        gsap.set(`.sec02 .layout .l1 .bu${String(i).padStart(2,'0')}`,{
            scaleY : 1,
        });

    }

    // 두번째 섹션 초기화

    gsap.set('.sec02 .layout li:nth-of-type(2) .block .lob .item',{
        x : 0,
        opacity : 1
    })
    $('.sec02 .layout .l2 .block .lob .item').removeAttr('style');
    $('.sec02 .layout .l2 .block .lob .left').removeAttr('style');
    $('.sec02 .layout .l2 .block .lob .right').removeAttr('style');

   
    // 원움직임 모바일버전
    gsap.utils.toArray('.cir-fi').forEach(e2=>{

        gsap.set($(e2).find('.lib'),{
            xPercent : 0,
            yPercent : 0,
        })

        $(e2).children().each((i,e)=>{
            gsap.fromTo(e,{
                xPercent : 0,
                yPercent : 0,
            },{
                xPercent : 0,
                yPercent : 0,
                scale : ()=>{
                    let scale = 1;
                    switch(i){
                        case 0 :
                            scale = 0.8;
                            break;
                        case 1 :
                            scale = 0.7;
                            break;
                        case 2 :
                            scale = 0.5;
                            break;
                    }
                    return scale;
                },
                ease : "power1.inOut",
                repeat : -1,
                yoyo : true,
                duration : ()=>{
                    let x = 0;
                    switch(i){
                        case 0 :
                            x = 1.4;
                            break;
                        case 1 :
                            x = 1.2;
                            break;
                        case 2 :
                            x = 1;
                            break;
                    }
                    return x;
                }
            })
        });

    });

    // 모바일버전 텍스트
    gsap.utils.toArray('.sec02 .layout li').forEach((e,i)=>{
        gsap.set($(e).find('.tbx .cir-fi'),{
            y :75,
            opacity : 0
        })
        gsap.set($(e).find('.tbx .h4'),{
            y :75,
            opacity : 0,
        })
        gsap.set($(e).find('.tbx .h3'),{
            y :75,
            opacity : 0
        })
        gsap.set($(e).find('.tbx .p'),{
            y :75,
            opacity : 0
        });


        const tl = gsap.timeline({
            scrollTrigger : {
                trigger : e,
                start : "top bottom-=15%",
                // markers : true,
            }
        })
        .to($(e).find('.tbx .cir-fi'),{
            y :0,
            opacity : 1
        })
        .to($(e).find('.tbx .h4'),{
            y :0,
            opacity : 1,
            onStart : ()=>{
                gsap.to($(e).find('.tbx .h4 span'),{
                    textContent: countArr[i],
                    duration: 1,
                    ease: "power1.in",
                    snap: { textContent: 1 },
                    stagger: {
                        each: 0.5,
                        /* onUpdate: function() {
                            this.targets()[0].innerHTML = numberWithCommas(Math.ceil(this.targets()[0].textContent));
                        }, */
                    }
                })
            },
        },">-=50%")
        .to($(e).find('.tbx .h3'),{
            y :0,
            opacity : 1
        },">-=50%")
        .to($(e).find('.tbx .p'),{
            y :0,
            opacity : 1
        },">-=50%");
        
    })
   }

    ScrollTrigger.matchMedia({
        "(min-width:860px)" : ()=>{
            if(!md_()){
                desktopScroll();
            }else{
                mobileScroll();
            }
        },
        "(max-width:860px)" : () => {
            $('.sec02').removeAttr('style');
            mobileScroll();
        },


        "all" : ()=>{
            // 첫번째 섹션 SVG 안에 원들 움직임
            gsap.utils.toArray('.sec02 .bu_circle .cir').forEach(e=>{
                $(e).children().each((i,e2)=>{
                    gsap.to(e2,{
                        transformOrigin : "center center",
                        scale : ()=>{
                            let scale = 1;
                            switch(i){
                                case 2 :
                                    scale = 0.8;
                                    break;
                                case 1 :
                                    scale = 0.7;
                                    break;
                                case 0 :
                                    scale = 0.5;
                                    break;
                            }
                            return scale;
                        },
                        ease : "power1.inOut",
                        repeat : -1,
                        yoyo : true,
                        duration : ()=>{
                            let x = 0;
                            switch(i){
                                case 2 :
                                    x = 1.4;
                                    break;
                                case 1 :
                                    x = 1.2;
                                    break;
                                case 0 :
                                    x = 1;
                                    break;
                            }
                            return x;
                        }
                    })
                });
            });
        }

    });

/*--------------------------------------------------------------

	//section 3	

--------------------------------------------------------------*/
const sec03DesktopScroll = ()=>{
	$('.sec03').css('height',`${100*2}vh`);

	ScrollTrigger.create({
		trigger : ".sec03 .pin",
		endTrigger : ".sec03",
		pin : true,
		// markers : true,
		end : "bottom bottom",
	})
	gsap.to('.sec03 .txt_box', {
		scrollTrigger: {
			trigger: '.sec03 .pin',
			start: 'top center',
			end: 'bottom center',
			scrub: true
		},
		opacity: '1' 
	})

	/*const sec3TL = gsap.timeline({
		scrollTrigger : {
			trigger : ".sec03",
			end : `top+=${$('.sec03').height()/4}`,
			invalidateOnRefresh : true,
			scrub : 1,
			toggleClass : {targets: '.sec03', className: "on"},
		},
	})*/
	
	
	/*.set('.sec03 .cir .line',{
		xPercent : -50,
		yPercent : 0,
		scale:0,
		left : "50%",
		top: "-50%"
	})
	.to('.sec03 .cir .line',{
		xPercent : -50,
		yPercent : -50,
		scale:1,
		left : "50%",
		top: "50%"
	})
	.to('.half_line .bar', {
		scrollTrigger: {
			trigger: '#sec03 .pin',
			start: 'top center',
			end: 'bottom center',
			scrub: true
		},
		height: '100%' // 상단부터 하단까지 이동
	})
	.to('.sec03 .half_line .circle',{
		scrollTrigger: {
			trigger: '#sec03 .pin',
			start: 'top center',
			end: 'bottom center',
			scrub: true
		},
		top: '99%' // 상단부터 하단까지 이동
	})*/
	
	/*ScrollTrigger.create({
		trigger : ".sec03 .pin",
		endTrigger : ".sec03",
		pin : true,
		// markers : true,
		end : "bottom bottom",
	})*/

}

const sec03MobileScroll = ()=>{
	$('.sec03').css('height',`auto`);

	 ScrollTrigger.create({
		trigger : ".sec03",
		start : "top top",
		end : "bottom bottom",
	})
}

	ScrollTrigger.matchMedia({
        "(min-width:861px)" : ()=>{
            if(!md_()){
                sec03DesktopScroll();
            }else{
                sec03MobileScroll();
            }
        },
        "(max-width:860px)" : ()=>{
            sec03MobileScroll();
        },
    });

	// 클릭 시 스크롤
	$('.sec03_sp').click(function() {
		gsap.to(window, { duration: 1, scrollTo: ".sec04" });
		return false;
	});
	// 클릭 시 스크롤
	$('.sec03_camp').click(function() {
		gsap.to(window, { duration: 1, scrollTo: ".sec05" });
		return false;
	});

/*--------------------------------------------------------------

	//section 4	

--------------------------------------------------------------*/
const sec04DesktopScroll = ()=>{
		 $('.sec04').children().each((i,e)=>{
            ScrollTrigger.create({
                trigger : '.sec04 .title',
                start : ()=>{
                    if (i == 0){
                        return `top top`
                    }else{
                        return `top+=${$('.sec04').outerHeight()/5} top`
                    }
                },
                onEnter : ()=>{
                    if(i == 1){
                        $('.sec04 .horizon').addClass('act');
                    }
					
                },
                onLeaveBack : ()=>{
                    if(i == 1){
                        $('.sec04 .horizon').removeClass('act');
                    }
                },
                // markers : true,
            });

        })

        let layer = gsap.utils.toArray('.sec04 .horizon .layer');

        layerTween = gsap.to(layer,{
            x : ()=>{
                let x = 0;
                layer.forEach(e=>{
                    x += e.clientWidth;
                })
                return -(x - (window.innerWidth - ( gsap.getProperty(layer[0],"paddingLeft") + gsap.getProperty('.sec04 .horizon','paddingLeft')) )); 
            },
            ease: "none", // <-- IMPORTANT!

            scrollTrigger: {
                trigger: ".sec04",
                pin: true,
                scrub: true,
                end: `+=${1000*layer.length}`,
                invalidateOnRefresh : true,
				toggleClass : {targets: '.sec04', className: "on"},
                // markers : true,
                onRefresh : (self)=>{
                    self.update();
                },
            }
			

        });

		gsap.to('.sec04 .water-fill', {
			scrollTrigger: {
				trigger: '.sec04',
				start: 'top center',
				end: 'bottom center',
				scrub: true
			},
			width: '180%',
		})

    }

    // 모바일
    const sec04MobileScroll = ()=>{
        ScrollTrigger.create({
            trigger : ".sec04",
            start : "top top",
            end : ()=>{
                return `bottom-=${$('.sec04 .horizon .layer').eq($('.sec04 .horizon .layer').length - 1).outerHeight()/2}px top`;
            },
        })
    }

    ScrollTrigger.matchMedia({
        "(min-width:861px)" : ()=>{
            if(!md_()){
                sec04DesktopScroll();
            }else{
                sec04MobileScroll();
            }
        },
        "(max-width:860px)" : ()=>{
            sec04MobileScroll();
        },
    });

/*--------------------------------------------------------------

	//section 5	

--------------------------------------------------------------*/
	const sec05DesktopScroll = ()=>{
		 $('.sec05').children().each((i,e)=>{
            ScrollTrigger.create({
                trigger : '.sec05 .title',
                start : ()=>{
                    if (i == 0){
                        return `top top`
                    }else{
                        return `top+=${$('.sec05').outerHeight()/2} top`
                    }
                },
                onEnter : ()=>{
                    if(i == 1){
                        $('.sec05 .horizon').addClass('act');
                    }
					
                },
                onLeaveBack : ()=>{
                    if(i == 1){
                        $('.sec05 .horizon').removeClass('act');
                    }
                },
                // markers : true,
            });

        })

        let layer = gsap.utils.toArray('.sec05 .horizon .layer');

        layerTween = gsap.to(layer,{
            x : ()=>{
                let x = 0;
                layer.forEach(e=>{
                    x += e.clientWidth;
                })
                return -(x - (window.innerWidth - ( gsap.getProperty(layer[0],"paddingLeft") + gsap.getProperty('.sec05 .horizon','paddingLeft')) )); 
            },
            ease: "none", // <-- IMPORTANT!

            scrollTrigger: {
                trigger: ".sec05",
                pin: true,
                scrub: true,
                end: `+=${2000*layer.length}`,
                invalidateOnRefresh : true,
				toggleClass : {targets: '.sec05', className: "on"},
                // markers : true,
                onRefresh : (self)=>{
                    self.update();
                },
            }

        });

		gsap.to('.sec05 .water-fill', {
			scrollTrigger: {
				trigger: '.sec05',
				start: 'top center',
				end: 'bottom center',
				scrub: true
			},
			width: '220%',
		})

    }

    // 모바일
    const sec05MobileScroll = ()=>{
        ScrollTrigger.create({
            trigger : ".sec05",
            start : "top top",
            end : ()=>{
                return `bottom-=${$('.sec05 .horizon .layer').eq($('.sec05 .horizon .layer').length - 1).outerHeight()/2}px top`;
            },
        })
    }

    ScrollTrigger.matchMedia({
        "(min-width:861px)" : ()=>{
            if(!md_()){
                sec05DesktopScroll();
            }else{
                sec05MobileScroll();
            }
        },
        "(max-width:860px)" : ()=>{
            sec05MobileScroll();
        },
    });

/*--------------------------------------------------------------

	//section 6	

--------------------------------------------------------------*/
	const sec06DesktopScroll = ()=>{

        gsap.set('.sec06 .ibx li',{
            y: 0,
            opacity : 1,
        })
	
		let layer = gsap.utils.toArray('.sec06 .ibx');

        layerTween = gsap.to(layer,{
            x : ()=>{
                let x = 0;
                layer.forEach(e=>{
                    x += e.clientWidth;
                })
                return -(x - (window.innerWidth - ( gsap.getProperty(layer[0],"paddingLeft") + gsap.getProperty('.sec06 .ibx','paddingLeft')) )); 
            },
            ease: "none", // <-- IMPORTANT!

            scrollTrigger: {
                trigger: ".sec06",
                pin: true,
                scrub: true,
                end: `+=${2500*layer.length}`,
                invalidateOnRefresh : true,
                // markers : true,
                onRefresh : (self)=>{
                    self.update();
                },
            }

        })
		gsap.to('.sec06_bg',{
			scrollTrigger: {
				trigger: '.sec06',
				start: 'top center',
				end: '80% center',
				scrub: true
			},
			scale: '2'
		})

 


        // 섹션1
        $('.sec06 .lasy').children().each((i,e)=>{
            ScrollTrigger.create({
                trigger : '.sec06',
                start : ()=>{
                    if (i == 0){
                        return `top top`
                    }else{
                        return `top+=${$('.sec06').outerHeight()/2} top`
                    }
                },
            });

        })

    }

    const sec06MobileScroll = ()=>{
        $('.sec06 .ibx li').removeAttr('style');

        gsap.set('.sec06 .ibx li',{
            x: 0,
        })

        gsap.utils.toArray(' .sec06 .ibx li').forEach(e=>{
            ScrollTrigger.create({
                trigger : e,
                invalidateOnRefresh : true,
                toggleClass : "act",
                // markers : true,
                start : "top center",
                end : "top top",
            })
        });

        gsap.utils.toArray('.sec06 .ibx li').forEach(e=>{
            gsap.from(e,{
                y : 70,
                opacity : 0,
                scrollTrigger : {
                    trigger : e,
                    start : "top bottom-=15%"
                }
            })
        })
    }

    // 회사소개
    ScrollTrigger.matchMedia({
        "(min-width:861px)" : ()=>{
            if(!md_()){
                sec06DesktopScroll();
            }else{
                sec06MobileScroll();
            }
        },
        "(max-width:860px)" : ()=>{
            sec06MobileScroll();
        },
    });
});

