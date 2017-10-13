var bus = new Vue();

Vue.component('data-list-li', {
    template: `
    <li>
        <a href="javascript:;" @click="selectData(da_id)" :data-id="da_id">
            <h6>
                <small>#{{ da_id }}</small>
                {{ mb_name }}({{ mb_id }})
            </h6>
            <p>
                {{ mb_hp }}
            </p>
            <p>
                {{ hp_name }}
                <small>{{ da_datetime }}</small>
            </p>
        </a>
    </li>
    `,
    props: ['item'],
    data: function() {
        return {
            da_id: this.item.da_id,
            mb_name: this.item.mb_name,
            mb_id: this.item.mb_id,
            mb_hp: this.item.mb_hp,
            hp_name: this.item.hp_name,
            da_datetime: this.item.da_datetime
        };
    },
    methods: {
        selectData: function(da_id) {
            $('.data-list a').removeClass('active');
            $('.data-list a[data-id=' + da_id + ']').addClass('active');

            bus.$emit('data-selected', da_id);
        }
    }
});

var DATA = new Vue({
    el: '#data-list',
    data: {
        da_id: '',
        stx: '',
        searchState: '',
        datas: [],
        offset: 0,
        rows: 10
    },
    watch: {
        stx: function() {
            this.searchState = '입력을 기다리는 중...';
            this.searchData();
        }
    },
    mounted: function() {
        this.getStx();
        this.searchData();
    },
    methods: {
        getStx: function() {
            var vm = this;

            axios({
                url: '/data',
                params: {
                    req: 'stx-get'
                }
            }).then(function(res) {
                if (res.data.res === 200) {
                    vm.stx = res.data.data;
                }
            }).catch(function (err) {
                console.log(err);
            });
        },
        searchData: _.debounce(
            function (more) {
                var vm = this;
                vm.searchState = '검색중...';
                if (!more) {
                    vm.datas = [];
                    vm.offset = 0;
                }
                if (!vm.stx) {
                    vm.searchState = '환자명 혹은 아이디 혹은 휴대전화 혹은 병의원명 혹은 데이터 등록 일자를 검색어에 작성해주세요.';
                    return false;
                }
                axios({
                    method: 'get',
                    url: '/data',
                    params: {
                        req: 'data-get',
                        stx: vm.stx,
                        offset: vm.offset,
                        rows: vm.rows
                    }
                }).then(function(res) {
                    console.log(res);
                    if (res.data.res === 400 || res.data.data.length === 0) {
                        vm.searchState = '검색 결과가 없습니다';
                    } else {
                        $(res.data.data).each(function(idx, arr) {
                            vm.datas.push(arr);
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
        }
    }
});

Vue.component('file-list-li', {
    template: `
    <li>
        <div v-show="isShow">
            <header class="file-check">
                <small>#{{ fi_id }} <strong>다운로드</strong> {{ fi_hit }}회</small>
            </header>
            <div v-if="isImg" class="file-image bg-offwhite text-center">
                <img :src="imgSrc" class="img-responsive">
            </div>
            <div v-else class="file-icon text-center">
                <i class="fa" :class="faIcon">
                    <p>{{ ext }}</p>
                </i>
            </div>
            <div class="file-content">
                <p class="section-padding" style="height:79px">{{ fi_name }} <small>({{ fi_size }})</small></p>
                <hr>
                <div class="section-padding text-right">
                    <button type="button" class="btn btn-sm btn-default" @click="downloadCart" v-show="isDownload === false"><i class="fa fa-fw fa-cloud-download"></i> 다운로드 담기</button>
                    <button type="button" class="btn btn-sm btn-primary" @click="downloadCancel" v-show="isDownload === true"><i class="fa fa-fw fa-ban"></i> 취소</button>
                </div>
            </div>
        </div>
    </li>
    `,
    props: ['item'],
    data: function() {
        return {
            fi_id: this.item.fi_id,
            fi_type: this.item.fi_type,
            imgSrc: this.item.imgSrc || '/img/logo.png',
            faIcon: this.item.faIcon || 'fa-file-o',
            isImg: this.item.imgSrc ? true : false,
            fi_name: this.item.fi_name,
            ext: this.item.ext,
            fi_size: this.item.fi_size,
            fi_hit: this.item.fi_hit,
            isDownload: this.item.isDownload,
            isShow: true
        };
    },
    watch: {
        isDownload: function() {
            bus.$emit('parentAddCart', this.fi_id);
        }
    },
    methods: {
        selectData: function(da_id) {
            $('.data-list a').removeClass('active');
            $('.data-list a[data-id=' + da_id + ']').addClass('active');

            bus.$emit('data-selected', da_id);
        },
        downloadCart: function() {
            this.isDownload = true;
        },
        downloadCancel: function() {
            this.isDownload = false;
        }
    }
});

var FILE = new Vue({
    el: '#file-tabs',
    data: {
        emptyShow: true,
        fileState: '먼저 데이터 리스트를 검색 한 후 파일을 확인하세요.',
        files: [],
        cart: [],
        da_id: null,
        fileType: 'all',
        checkAllShow: true,
        ce_id: null,
        ce_certification: null
    },
    mounted: function() {
        var vm = this;
        bus.$on('data-selected', function(da_id) {
            vm.da_id = da_id;
            vm.fileGet();
        });

        bus.$on('parentAddCart', function(da_id) {
            if (vm.cart.indexOf(da_id) === -1) {
                vm.cart.push(da_id);
            } else {
                vm.cart.splice(vm.cart.indexOf(da_id), 1);
            }
        });
    },
    methods: {
        fileGet: function() {
            var vm = this;
            vm.files = [];
            vm.emptyShow = true;
            vm.fileState = '파일 데이터를 불러오는 중입니다...';

            var offset = $(this.$el).offset();
            $('html, body').animate({scrollTop : offset.top - 60}, 400);

            axios({
                method: 'get',
                url: '/data',
                params: {
                    req: 'file-get',
                    da_id: vm.da_id,
                    file_type: vm.fileType
                }
            }).then(function(res) {

                if (res.data.data.length === 0) {
                    vm.fileState = '아직 등록된 파일이 없네요.';
                } else {
                    vm.emptyShow = false;
                    $(res.data.data).each(function(idx, arr) {
                        vm.files.push(arr);
                    });
                }
            }).catch(function(err) {
                console.log('error: ' + err);
            });;
        },
        isShowCheck: function(filetype) {
            var vm = this;

            $('.tabs a').removeClass('active');
            $('.tabs a[data-type=' + filetype + ']').addClass('active');
            vm.fileType = filetype;
            vm.fileGet();
        },
        checkAll: function() {
            var vm = this;

            if (vm.$refs.li) {
                vm.$refs.li.forEach(function(child, idx) {
                    vm.checkAllShow = false;
                    child.isDownload = true;
                });
            }
        },
        deleteAll: function() {
            var vm = this;

            if (vm.$refs.li) {
                vm.$refs.li.forEach(function(child, idx) {
                    vm.checkAllShow = true;
                    child.isDownload = false;
                });
            }
        },
        downloadConfirm: function() {
            var vm = this;

            if (vm.cart.length > 0) {
                swal({
                    title: '다운로드 인증',
                    text: '다운로드를 위해 환자에게 인증을 요청하시겠어요?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-info btn-raised',
                    confirmButtonText: '네, 요청해요!',
                    cancelButtonText: '아니요',
                    closeOnConfirm: false
                },
                function() {
                    vm.certDownload();
                });
            } else {
                swal({
                    title: '파일 선택',
                    text: '다운로드 받으실 파일을 먼저 선택해주세요.',
                    type: 'warning'
                });
            }
        },
        certDownload: function() {
            var vm = this;
            console.log(vm.da_id);
            axios({
                method: 'post',
                url: '/cert',
                params: {
                    req: 'cert-download',
                    da_id: vm.da_id
                }
            }).then(function(res) {
                console.log(res);
                if (res.data.res === 400 || !res.data.data.ce_id) {
                    swal({
                        title: '인증 실패',
                        text: '인증에 실패했어요. 다시 시도해주세요.',
                        type: 'error'
                    });
                    return false;
                }
                vm.ce_id = res.data.data.ce_id;

                if (res.data.data.certification) {
                    vm.ce_certification = res.data.data.certification;
                    vm.certConfirm();
                    return true;
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
                    text: '선택한 파일을 압축중입니다. 잠시만 기다려 주세요.',
                    type: 'success',
                    timer: 5000,
                    showConfirmButton: false
                });

                vm.dataGet();
            }).catch(function(err) {
                console.log(err);
            });
        },
        dataGet: function() {
            var vm = this;

            axios({
                method: 'post',
                url: '/data/download',
                params: {
                    req: 'data-download',
                    da_id: vm.da_id,
                    cart: vm.cart
                }
            }).then(function(res) {
                console.log(res);
                if (res.data.res === 400 || !res.data.data.download_url) {
                    swal({
                        title: '다운로드 실패!',
                        text: '다운로드를 실패했습니다. 다시 시도해주세요.',
                        type: 'error'
                    });
                    return false;
                }
                swal({
                    title: '압축성공!',
                    text: '다운로드를 실행합니다.',
                    type: 'success'
                });
                $('#download').attr('src', res.data.data.download_url);

            }).catch(function(err) {
                console.log(err);
            });
        }
    }
});
