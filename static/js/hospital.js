var HOSPITAL = new Vue({
    el: '#hospital',
    data: {
        req: 'hospital-set',
        hp_id: null,
        hp_name: null,
        mb_id: null,
        registerShow: false,
        registerText: '등록',
        registerTitle: '새로운 병의원 추가'
    },
    mounted: function() {
    },
    watch: {
        registerShow: function() {
            var vm = this;

            if (vm.registerShow) {
                $('.register-new').removeClass('btn-danger').addClass('btn-default');
                $('.register-new i').removeClass('fa-hospital-o').addClass('fa-times');
            } else {
                $('.register-new').removeClass('btn-default').addClass('btn-danger');
                $('.register-new i').removeClass('fa-times').addClass('fa-hospital-o');
            }

            setTimeout(function() {
                $('#hp_name').focus();
            }, 100);
        },
        req: function() {
            var vm = this;

            vm.registerShow = true;
            setTimeout(function() {
                $('#hp_name').focus();
            }, 100);

            if (vm.req === 'hospital-set') {
                vm.hp_id = vm.hp_name = vm.mb_id = null;
                vm.registerText = '등록';
                vm.registerTitle = '새로운 병의원 추가';
            } else {
                vm.registerText = '수정';
                vm.registerTitle = '병의원 정보 수정';
            }
        }
    },
    methods: {
        registerNew: function() {
            var vm = this;
            vm.req = 'hospital-set';
            vm.registerShow = !vm.registerShow;
        },
        submit: function() {
            var vm = this;

            if (!vm.hp_name) {
                swal({
                    title: '오류!',
                    text: '병의원 이름을 작성해주세요.',
                    type: 'warning'
                }, function() {
                    setTimeout(function() {
                        $('#hp_name').focus();
                    }, 100);
                });

                return false;
            }

            if (vm.req === 'hospital-set') {
                axios({
                    method: 'post',
                    url: '/hospital',
                    params: {
                        req: 'hospital-set',
                        hp_name: vm.hp_name,
                        mb_id: vm.mb_id
                    }
                }).then(function(res) {
                    console.log(res);
                    if (res.data.res === 400) {
                        swal({
                            title: '오류!',
                            text: res.data.error,
                            type: 'error'
                        });
                        return false;
                    }
                    swal({
                        title: '성공!',
                        text: '새로운 병의원을 등록했습니다.',
                        type: 'success'
                    }, function() {
                        location.reload();
                    });

                }).catch(function(err) {
                    console.log(err);
                });
            } else {
                if (!vm.hp_id) {
                    swal({
                        title: '오류!',
                        text: '병의원을 선택하고 수정해주세요.',
                        type: 'warning'
                    }, function() {
                        setTimeout(function() {
                            $('#hp_name').focus();
                        }, 100);
                    });

                    return false;
                }

                if (!vm.mb_id) {
                    swal({
                        title: '오류!',
                        text: '소유자 아이디를 작성해주세요.',
                        type: 'warning'
                    }, function() {
                        setTimeout(function() {
                            $('#mb_id').focus();
                        }, 100);
                    });

                    return false;
                }

                axios({
                    method: 'post',
                    url: '/hospital',
                    params: {
                        req: 'hospital-modify',
                        hp_id: vm.hp_id,
                        hp_name: vm.hp_name,
                        mb_id: vm.mb_id
                    }
                }).then(function(res) {
                    console.log(res);
                    if (res.data.res === 400) {
                        swal({
                            title: '오류!',
                            text: res.data.error,
                            type: 'error'
                        });
                        return false;
                    }
                    swal({
                        title: '성공!',
                        text: '병의원정보를 수정 했습니다.',
                        type: 'success'
                    }, function() {
                        location.reload();
                    });
                });
            }
        },
        modify: function(hp_id) {
            var vm = this;

            vm.req = 'hospital-modify';
            vm.hp_id = hp_id;

            axios({
                method: 'get',
                url: '/hospital',
                params: {
                    req: 'hospital-get',
                    hp_id: vm.hp_id
                }
            }).then(function(res) {
                if (res.data.res === 400) {
                    swal({
                        title: '오류!',
                        text: res.data.error,
                        type: 'error'
                    });

                    return false;
                }
                var hp = res.data.data;
                vm.hp_id = hp.hp_id;
                vm.hp_name = hp.hp_name;
                vm.mb_id = hp.mb_id;

            }).catch(function(err) {
                console.log(err);
            });
        }
    }
});
