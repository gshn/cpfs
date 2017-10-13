var certCount = new Vue({
    el: '#cert-count',
    data: {
        certShow: false,
        certCount: 0,
        certInterval: false,
        ce_id: null,
        ce_certification: null,
        showModal: false
    },
    watch: {
        certCount: function() {
            this.certShow = this.certCount > 0 ? true : false;
        }
    },
    mounted: function() {
        var vm = this;
        clearInterval(vm.certInterval);
        vm.certInterval = setInterval(function() {
            vm.certConfirmQuery();
        }, 5000);
    },
    methods: {
        certConfirmQuery: function() {
            var vm = this;

            axios({
                method: 'get',
                url: '/cert',
                params: {
                    req: 'cert-confirm-query'
                }
            }).then(function(res) {
                console.log(res);
                var json = res.data;
                if (json.res === 400) {
                    console.log(json.error);
                    return false;
                }
                if (json.data.ce_type === '업로드' || json.data.ce_type === '다운로드') {
                    swal({
                        title: json.data.ce_certification,
                        text: '인증 요청이 발급되었습니다. 인증번호를 기입하면 일정 금액의 과금이 발생합니다. 계속 하시겠습니까?',
                        type: 'info'
                    }, function() {
                        location.reload();
                    });

                    swal({
                        title: json.data.ce_certification,
                        text: '인증 요청이 발급되었습니다. 인증번호를 기입하면 일정 금액의 과금이 발생합니다. 계속 하시겠습니까?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonClass: 'btn-info btn-raised',
                        confirmButtonText: '네, 알겠습니다!',
                        cancelButtonText: '취소',
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            swal({
                                title: json.data.ce_certification,
                                text: '의료진에게 인증번호를 알려주세요.',
                                type: 'success'
                            });
                        } else {
                            vm.certCancel(json.data.ce_id, json.data.ce_certification);
                        }
                    });
                    clearInterval(vm.certInterval);
                } else if (json.data.ce_type === '본인인증') {
                    swal({
                        title: '인증번호',
                        text: '전달 받은 인증번호를 입력해주세요.',
                        type: 'input',
                        showCancelButton: true,
                        closeOnConfirm: false,
                        confirmButtonText: '인증!',
                        cancelButtonText: '취소',
                        animation: 'slide-from-top',
                        inputPlaceholder: '인증번호'
                    },
                    function(inputValue){
                        if (inputValue === false) return false;
                        if (inputValue === '') {
                            swal.showInputError('인증번호를 입력해주세요!');
                            return false
                        }
                        vm.ce_id = json.data.ce_id;
                        vm.ce_certification = inputValue;
                        vm.certConfirm();
                    });
                    clearInterval(vm.certInterval);
                }

                vm.certCount = json.data.certCount;
            }).catch(function(err) {
                console.log(err);
            });
        },
        certConfirm: function() {
            var vm = this;

            axios({
                method: 'get',
                url: '/cert',
                params: {
                    req: 'cert-confirm',
                    ce_id: vm.ce_id,
                    ce_certification: vm.ce_certification
                }
            }).then(function(res) {
                console.log(res);

                if (res.data.res === 400 || (vm.ce_certification !== res.data.data.ce_certification)) {
                    swal({
                        title: '인증 실패',
                        text: '인증에 실패했어요. 다시 시도해주세요.',
                        type: 'error'
                    });
                    return false;
                }

                swal({
                    title: '인증성공!',
                    text: '이제 R2Cloud를 이용하실 수 있어요.',
                    type: 'success'
                });

            }).catch(function(err) {
                console.log(err);
            });
        },
        certCancel: function(ce_id, ce_certification) {
            axios({
                method: 'get',
                url: '/cert',
                params: {
                    req: 'cert-cancel',
                    ce_id: ce_id,
                    ce_certification: ce_certification
                }
            }).then(function(res) {
                console.log(res);
                location.reload();
            }).catch(function(err) {
                console.log(err);
            });
        }
    }
});
