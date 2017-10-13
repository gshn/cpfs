var login = new Vue({
    el: '#login',
    data: {
        findTimes: 0,
        findId: '',
        findBirth: '1970-01-01',
        findSex: '남자',
        number: '',
        loginType: '로그인',
        loginAction: '/user/login',
        findSubmitText: '인증번호 받기',
        findIdDisabled: false,
        numberDisabled: true,
        timer: false,
        certTime: 0,
        timerText: '(2:00)'
    },
    methods: {
        find: function() {
            this.loginType = this.loginType === '로그인' ? '비밀번호찾기' : '로그인';
            this.loginAction = this.loginType === '로그인' ? '/user/login' : '/user/find';
        },
        register: function() {
            this.loginType = this.loginType === '병의원가입신청' ? '로그인' : '병의원가입신청';
            this.loginAction = this.loginType === '병의원가입신청' ? '/user/register' : '/user/login';
        },
        startCertTimer: function() {
            var vm = this;
            vm.certTime -= 1;
            var min = Math.floor(vm.certTime / 60);
            var sec = (vm.certTime % 60) - 0;
            sec = (sec > 9) ? sec : '0' + sec;

            vm.timerText = '(' + min + ':' + sec + ')';

            if (vm.timer === true && vm.certTime <= 0) {
                swal({
                    title: '시간초과!',
                    text: '인증시간이 초과 했습니다. 다시 요청해주세요.',
                    type: 'warning'
                }, function() {
                    vm.loginType = '비밀번호찾기';
                    vm.findIdDisabled = false;
                    vm.numberDisabled = true;
                    vm.findSubmitText = '인증번호 받기';
                });
                vm.timer = false;
                clearInterval(vm.timerInterval);
            }
        },
        timerInterval: function() {
            var vm = this;
            vm.timerInterval = setInterval(function() {
                vm.startCertTimer();
            }, 1000);
        },
        sendNumber: function() {
            var vm = this;

            if (vm.numberDisabled) {
                if (!vm.findId) {
                    swal({
                        title: '오류!',
                        text: '휴대전화 혹은 아이디를 입력해주세요.',
                        type: 'warning'
                    },
                    function() {
                        setTimeout(function() {
                            $('#findId').focus();
                        }, 100);
                    });
                    return false;
                }
                vm.findTimes += 1;
                if (vm.findTimes > 3) {
                    swal({
                        title: '에러!',
                        text: '빠른시간 내에 너무 많은 시도를 했어요. 새로고침 후 사용해주세요.',
                        type: 'error'
                    });
                    return false;
                }

                $.ajax({
                    url: '/user/find',
                    type: 'post',
                    data: {
                        loginType: vm.loginType,
                        findId: vm.findId,
                        findBirth: vm.findBirth,
                        findSex: vm.findSex
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.res === 200) {
                            swal({
                                title: '성공!',
                                text: '인증번호를 발송했습니다.',
                                type: 'success'
                            },
                            function() {
                                setTimeout(function() {
                                    $('#number').focus();
                                }, 100);
                            });
                            vm.loginType = '인증번호확인';
                            vm.findIdDisabled = true;
                            vm.numberDisabled = false;
                            vm.findSubmitText = '인증번호 전송하기';

                            vm.timer = true;
                            vm.certTime = 60 * 2;
                            clearInterval(vm.timerInterval);
                            vm.timerInterval = setInterval(function() {
                                vm.startCertTimer();
                            }, 1000);
                        } else {
                            swal('오류!', data.error, 'warning');
                            return false;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                if (!vm.number) {
                    swal({
                        title: '오류!',
                        text: '전송받은 인증번호를 입력해주세요.',
                        type: 'warning'
                    },
                    function() {
                        setTimeout(function() {
                            $('#number').focus();
                        }, 100);
                    });
                    return false;
                }

                $.ajax({
                    url: '/user/find',
                    type: 'post',
                    data: {
                        loginType: vm.loginType,
                        findId: vm.findId,
                        findBirth: vm.findBirth,
                        findSex: vm.findSex,
                        number: vm.number
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.res === 200) {
                            swal({
                                title:  data.data.lostPassword,
                                text: '임시 비밀번호 6자리를 발급했습니다. 로그인 후 반드시 비밀번호를 변경해주세요.',
                                type: 'success'
                            },
                            function() {
                                vm.loginType = '로그인';
                                vm.loginAction = '/user/login';

                                setTimeout(function() {
                                    $('#id').val(vm.findId);
                                    $('#pw').val(data.data.lostPassword);
                                }, 100);
                            });
                        } else {
                            swal('오류!', data.error, 'warning');
                            return false;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }
    }
});
