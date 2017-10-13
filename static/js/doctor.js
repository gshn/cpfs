var PATIENT_REGISTER = new Vue({
    el: '#patient-register',
    data: {
        step: 'step1',
        hp_id: 1,
        mb_id: null,
        mb_password: null,
        mb_name: null,
        mb_level: 3,
        mb_hp: null,
        ce_id: null,
        number: '',
        findSubmitText: '회원 가입 및 인증번호 받기',
        findIdDisabled: false,
        numberDisabled: true,
        passwdShow: false
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
                    text: '아이디를 입력해주세요.',
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
                    text: '이름을 입력해주세요.',
                    type: 'warning'
                },
                function() {
                    setTimeout(function() {
                        $('#mb_name').focus();
                    }, 100);
                });
                return false;
            }
            if (!vm.mb_hp) {
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
                    url: '/doctor',
                    type: 'post',
                    data: {
                        req: 'doctor-set',
                        hp_id: vm.hp_id,
                        mb_id: vm.mb_id,
                        mb_password: vm.mb_password,
                        mb_name: vm.mb_name,
                        mb_hp: vm.mb_hp,
                        mb_level: vm.mb_level
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
                    url: '/cert',
                    params: {
                        req: 'cert-confirm',
                        ce_id: vm.ce_id,
                        ce_certification: vm.number
                    }
                }).then(function(res) {
                    console.log(res);
                    if (res.data.res === 200) {
                        vm.next('step3');
                    }
                }).catch(function(err) {
                });
            }
        }
    }
});
var PATIENT = new Vue({
    el: '#patient-set',
    data: {
    },
    watch: {
    },
    mounted: function() {
        var vm = this;
    },
    methods: {
        confirmType: function() {
            var vm = this;
            swal({
                title: '환자등록',
                text: 'R2Cloud에 처음 등록하는 환자인가요?',
                type: 'info',
                showCancelButton: true,
                confirmButtonClass: 'btn-info btn-raised',
                confirmButtonText: '네, 처음 등록 해요!',
                cancelButtonText: '아니요',
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    location.href = '/patient/register';
                } else {
                    vm.patientModify();
                }
            });
        },
        patientModify: function() {
            // var vm = this;
            // swal({
            //     title: '병의원 변경',
            //     text: '기존에 저장된 데이터를 모두 이전 하시겠어요?',
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonClass: 'btn-info btn-raised',
            //     confirmButtonText: '네, 이전 할께요!',
            //     cancelButtonText: '아니요',
            //     closeOnConfirm: false
            // }, function(isConfirm) {
            //     if (isConfirm) {
            //         swal({
            //             title: '변경!',
            //             text: 'R2Cloud 병의원 변경 페이지로 이동합니다.',
            //             type: 'success'
            //         }, function() {
            //             location.href = '/patient/migration';
            //         });
            //     } else {
            //         return false;
            //     }
            // });
        }
    }
});
