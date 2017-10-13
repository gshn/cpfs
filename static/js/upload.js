var bus = new Vue();

Vue.component('patient-list-li', {
    template: `
    <li>
        <a href="javascript:;" @click="selectData(mb_no)" :data-id="mb_no">
            <h6>
                <small>#{{ mb_no }}</small>
                {{ mb_name }}({{ mb_id }})
            </h6>
            <p>
                <small>{{ mb_hp }}</small>
            </p>
        </a>
    </li>
    `,
    props: ['item'],
    data: function() {
        return {
            mb_no: this.item.mb_no,
            mb_name: this.item.mb_name,
            mb_id: this.item.mb_id,
            hp_name: this.item.hp_name,
            mb_hp: this.item.mb_hp,
        };
    },
    methods: {
        selectData: function(mb_no) {
            var vm = this;
            $('.patient-list a').removeClass('active');
            $('.patient-list a[data-id=' + mb_no + ']').addClass('active');

            swal({
                title: '업로드 인증',
                text: '업로드를 위해 환자에게 인증을 요청하시겠어요?',
                type: 'info',
                showCancelButton: true,
                confirmButtonClass: 'btn-info btn-raised',
                confirmButtonText: '네, 요청해요!',
                cancelButtonText: '아니요',
                closeOnConfirm: false
            },
            function() {
                bus.$emit('cert-upload', mb_no);
            });
        }
    }
});

var PATIENT = new Vue({
    el: '#patient-list',
    data: {
        mb_no: null,
        ce_id: null,
        ce_certification: null,
        stx: '',
        searchState: '',
        patients: [],
        offset: 0,
        rows: 10,
        params: {
            req: 'data-upload',
            da_id: null
        }
    },
    watch: {
        stx: function() {
            this.searchState = '입력을 기다리는 중...';
            this.searchData();
        }
    },
    mounted: function() {
        var vm = this;
        vm.searchData();

        bus.$on('data-set', function(mb_no) {
            vm.mb_no = mb_no;
            vm.dataSet();
        });

        bus.$on('cert-upload', function(mb_no) {
            vm.mb_no = mb_no;
            vm.certUpload();
        });
    },
    methods: {
        certUpload: function() {
            var vm = this;
            axios({
                method: 'post',
                url: '/cert',
                params: {
                    req: 'cert-upload',
                    mb_no: vm.mb_no
                }
            }).then(function(res) {
                console.log(res);
                vm.ce_id = res.data.data.ce_id;
                if (!vm.ce_id) {
                    swal({
                        title: '인증 발송 실패',
                        text: '인증번호 발송에 실패했어요. 다시 시도해주세요.',
                        type: 'error'
                    });
                    return false;
                }

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
                    vm.ce_certification = inputValue;
                    vm.certConfirm();
                });
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
                    text: '이제 업로드 하실 파일을 선택해주세요.',
                    type: 'success'
                });

                vm.dataSet();
            }).catch(function(err) {
                console.log(err);
            });
        },
        dataSet: function() {
            var vm = this;

            axios({
                method: 'post',
                url: '/data/upload',
                params: {
                    req: 'data-set',
                    mb_no: vm.mb_no
                }
            }).then(function(res) {
                console.log(res);
                if (res.data.data.length === 0) {
                    vm.searchState = '에러가 발생했습니다. 다시 인증 해주세요.';
                } else {
                    vm.params.da_id = res.data.data.da_id;
                    vm.searchState = '인증 완료!';
                    vm.upload();
                }
            }).catch(function(error) {
                vm.searchState = '에러! API 요청에 오류가 있습니다. ' + error;
            });
        },
        searchData: _.debounce(
            function (more) {
                var vm = this;
                vm.searchState = '검색중...';
                if (!more) {
                    vm.patients = [];
                    vm.offset = 0;
                }
                if (!vm.stx) {
                    vm.searchState = '환자명 혹은 아이디 혹은 휴대전화 번호를 검색해주세요.';
                    return false;
                }
                axios({
                    method: 'get',
                    url: '/data/upload',
                    params: {
                        req: 'patient-get',
                        stx: vm.stx,
                        offset: vm.offset,
                        rows: vm.rows
                    }
                }).then(function(res) {
                    if (res.data.data.length === 0) {
                        vm.searchState = '검색 결과가 없습니다';
                    } else {
                        $(res.data.data).each(function(idx, arr) {
                            vm.patients.push(arr);
                            vm.offset += 1;
                        });
                        vm.searchState = '더 보기';
                    }
                }).catch(function(error) {
                    vm.searchState = '에러! API 요청에 오류가 있습니다. ' + error;
                });
            }, 500
        ),
        more: function() {
            this.searchData(true);
        },
        upload: function() {
            var vm = this;
            $('#uploader').plupload({
                runtimes : 'html5',
                url : '/data/upload',
                max_file_count: 0,
                chunk_size: '10mb',
                filters : {
                    max_file_size : '1gb',
                    mime_types: [
                        { title: 'image files', extensions: 'jpg,gif,png,jpeg' },
                        { title: 'ct files', extensions: 'dcm' },
                        { title: 'r2gate files', extensions: 'proj,stl' },
                        { title: 'text files', extensions: 'hwp,doc,ppt,xls,docx,pptx,xlsx,txt' },
                        { title: 'etc files', extensions: 'zip' }
                    ],
                    prevent_duplicates: true
                },
                multipart_params: vm.params,
                rename: true,
                sortable: true,
                dragdrop: true,
                views: {
                    list: true,
                    thumbs: true,
                    active: 'thumbs'
                },
                init : {
                    BeforeUpload: function(up, file) {
                        // 파일이 업로드되기 바로 전에 발생합니다. 처리기에서 false를 반환하여 지정된 파일에 대한 업로드를 취소하는 데 사용할 수 있습니다.
                        // console.log('BeforeUpload=========');
                        // console.log(up);
                        // console.log(file);
                        // console.log('=====================');
                        if (vm.params.da_id === null || vm.ce_id === null || vm.ce_certification === null) {
                            swal({
                                title: '인증 필요',
                                text: '업로드에는 인증이 필요합니다.',
                                type: 'error'
                            });
                            return false;
                        }
                    },
                    UploadProgress: function(up, file) {
                        //파일이 업로드되는 동안 실행됩니다. 이 이벤트를 사용하여 현재 파일 업로드 진행 상황을 업데이트하십시오.
                        // console.log('UploadProgress=======');
                        // console.log(up);
                        // console.log(file);
                        // console.log('=====================');
                    },
                    BeforeChunkUpload: function(up, file, args, blob, offset) {
                        // 10mb 이상 파일이 올라가기 전
                        // console.log('BeforeChunkUpload====');
                        // console.log(up);
                        // console.log(file);
                        // console.log(args);
                        // console.log(blob);
                        // console.log('=====================');
                    },
                    ChunkUploaded: function(up, file, res) {
                        // 10mb 이상 파일 부분이 업로드 되었을 때
                        // console.log('ChunkUploaded========');
                        // console.log(up);
                        // console.log(file);
                        // console.log(res);
                        // console.log('=====================');
                    },
                    FileUploaded: function(up, file, res) {
                        // 개별 파일이 성공적으로 업로드되면 실행됩니다.
                        // console.log('FileUploaded=========');
                        // console.log(up);
                        // console.log(file);
                        // console.log(res);
                        // console.log('=====================');
                    },
                    UploadComplete: function(up, file, res) {
                        // 큐의 모든 파일이 업로드 될 때 발생합니다.
                        // console.log('UploadComplete=======');
                        // console.log(up);
                        // console.log(file);
                        // console.log(res);
                        // console.log('=====================');
                        swal({
                            title: '업로드 성공!',
                            text: '데이터 페이지로 이동합니다.',
                            type: 'success'
                        });
                        setTimeout(function() {
                            location.replace('/data');
                        }, 1000);
                    },
                    Error: function(up, err) {
                        // 오류가 발생할 때 발생합니다.
                        // console.log('Error================');
                        // console.log(up);
                        // console.log(err);
                        // console.log('=====================');
                    }
                }
            });
        }
    }
});
