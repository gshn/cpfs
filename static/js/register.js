var PATIENT_REGISTER = new Vue({
    el: '#patient-register',
    data: {
        name_type: '이름',
        req_type: 'patient',
        step: 'step1',
        mb_id: null,
        mb_password: null,
        mb_name: null,
        ce_id: null,
        mb_hp: null,
        number: '',
        findSubmitText: '회원 가입 및 인증번호 받기',
        findIdDisabled: false,
        numberDisabled: true,
        passwdShow: false
    },
    mounted: function() {
        var vm = this;
        if (location.pathname === '/user/register/hospital') {
            vm.name_type = '병의원명';
            vm.req_type = 'hospital';
        }
    },
    watch: {
        step: function() {
            var vm = this;
            $('.tabs a').removeClass('active');
            $('.tabs a[data-step=' + vm.step + ']').addClass('active');
        },
        passwdShow: function() {
            var vm = this;
            $('.passwdView button').toggleClass('btn-default btn-warning');
            $('.passwdView i').toggleClass('fa-eye fa-eye-slash');
            if (vm.passwdShow) {
                $('#mb_password').attr('type', 'text');
            } else {
                $('#mb_password').attr('type', 'password');
            }
        }
    },
    methods: {
        next: function(step) {
            this.step = step;
        },
        agree: function(obj) {
            $('.' + obj).toggleClass('btn-default').toggleClass('btn-success');
        },
        passwdView: function() {
            this.passwdShow = !this.passwdShow;
        },
        sendNumber: function() {
            var vm = this;

            if (!vm.mb_id) {
                swal({
                    title: '오류!',
                    text: '이메일을 입력해주세요.',
                    type: 'warning'
                },
                function() {
                    setTimeout(function() {
                        $('#mb_id').focus();
                    }, 100);
                });
                return false;
            }
            if (!vm.mb_password) {
                swal({
                    title: '오류!',
                    text: '비밀번호를 입력해주세요.',
                    type: 'warning'
                },
                function() {
                    setTimeout(function() {
                        $('#mb_password').focus();
                    }, 100);
                });
                return false;
            }
            if (!vm.mb_name) {
                swal({
                    title: '오류!',
                    text: vm.name_type + '을 입력해주세요.',
                    type: 'warning'
                },
                function() {
                    setTimeout(function() {
                        $('#mb_name').focus();
                    }, 100);
                });
                return false;
            }
            if (vm.req_type === 'patient' && !vm.mb_hp) {
                swal({
                    title: '오류!',
                    text: '휴대전화를 입력해주세요.',
                    type: 'warning'
                },
                function() {
                    setTimeout(function() {
                        $('#mb_hp').focus();
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

            if (vm.numberDisabled) {

                $.ajax({
                    url: '/user/register/' + vm.req_type,
                    type: 'post',
                    data: {
                        req: vm.req_type + '-set',
                        mb_id: vm.mb_id,
                        mb_password: vm.mb_password,
                        mb_name: vm.mb_name,
                        mb_hp: vm.mb_hp
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.res === 200) {
                            var text = '휴대전화(' + vm.mb_hp + ')로 인증번호를 발송했습니다.';
                            if (vm.req_type === 'hospital') {
                                text = '메일(' + vm.mb_id + ')로 인증번호를 발송했습니다.';
                            }

                            swal({
                                title: '성공!',
                                text: text,
                                type: 'success'
                            },
                            function() {
                                setTimeout(function() {
                                    $('#number').focus();
                                }, 100);
                            });

                            vm.ce_id = data.data.ce_id;
                            vm.loginType = '인증번호확인';
                            vm.findIdDisabled = true;
                            vm.numberDisabled = false;
                            vm.findSubmitText = '인증번호 전송하기';
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
                axios({
                    method: 'post',
                    url: '/user/register/' + vm.req_type,
                    params: {
                        req: 'cert-confirm-' + vm.req_type,
                        ce_id: vm.ce_id,
                        ce_certification: vm.number
                    }
                }).then(function(res) {
                    console.log(res);
                    if (res.data.res === 200) {
                        vm.next('step3');
                    } else {
                        swal({
                            'title': '오류!',
                            'text': res.data.error,
                            'type': 'warning'
                        });

                        return false;
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        }
    }
});
