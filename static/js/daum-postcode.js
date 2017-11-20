$(document).ready(
    function() {
        //아래 코드처럼 테마 객체를 생성합니다.(color값은 #F00, #FF0000 형식으로 입력하세요.)
        //변경되지 않는 색상의 경우 주석 또는 제거하시거나 값을 공백으로 하시면 됩니다.
        var themeObj = {
            bgColor: "#FFFFFF", //바탕 배경색
            //searchBgColor: "", //검색창 배경색
            //contentBgColor: "", //본문 배경색(검색결과,결과없음,첫화면,검색서제스트)
            pageBgColor: "#FFFFFF", //페이지 배경색
            //textColor: "", //기본 글자색
            //queryTextColor: "", //검색창 글자색
            postcodeTextColor: "#666666", //우편번호 글자색
            //emphTextColor: "", //강조 글자색
            outlineColor: "#FFFFFF" //테두리
        };

        var element_layer = document.getElementById('layer');

        new daum.Postcode({
            theme: themeObj,
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var zipcode = data.zonecode;
                var roadAddress = ''; // 신주소
                var jibunAddress = ''; // 구주소
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수
                var sigungu = ''; // 시군구 명 변수
                var bname = ''; // 읍면동 명 변수

                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }

                roadAddress = data.roadAddress;
                jibunAddress = data.jibunAddress;

                sigungu = data.sigungu;
                bname = data.bname;

                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if (data.userSelectedType === 'R') {
                    //법정동명이 있을 경우 추가한다.
                    if (data.bname !== '') {
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if (data.buildingName !== '') {
                        extraAddr += (extraAddr !== '' ? ', ' +
                            data.buildingName :
                            data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' (' +
                        extraAddr + ')' : '');
                }

                window.GetAddress.sendAddress(zipcode, fullAddr,
                    roadAddress, jibunAddress, extraAddr,
                    sigungu, bname);
            },
            width: '100%',
            height: '100%',
    }).embed(element_layer);

    $(element_layer).find('iframe').contents().find('iframe')
        .contents().find('.daum_popup').ready(function() {
            setTimeout(function() {
                window.GetAddress.pageLoadComplete();
            }, 250);
    });
});
