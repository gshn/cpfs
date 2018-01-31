# CPFS
> Cozmo PHP Fire Starter

## 개요
개발을 빠르게 진행 하도록 도와주는 PHP 프레임워크 입니다.
약간의 문법과 구조를 익히면 데이터베이스에 칼럼 하나 추가 했을 경우 모델 클래스에 프로퍼티 하나만 추가해도 CRUD가 자동으로 가능합니다.

기존 PHP 날코딩 대비 50%의 개발 시간 단축 효과가 있습니다. ~순전히 개인적인 생각..~

## 요구사양
최소 PHP 버전 5.6 이상에서 사용 가능합니다.

## 권장사양
현재 안정화 버전인 PHP 7.2에서 테스트 되고 있습니다.

## 설치방법
> 1. git clone으로 소스 설치
```
git clone https://github.com/gshn/cpfs app
```

> 2. public/index.php 를 document root로 서버 설정을 해야합니다.

> 3. nginx나 apache .htaccess를 통해서 index.php를 제거하는 rewrite 설정을 허용해야합니다.

> 4. src/config.conf 를 src/config.php로 복사해서 파일을 생성하세요.

> 5. src/config.php 파일을 수정해서 전역적으로 사용할 변수들을 수정해주세요.

> 6. http://domain/install 로 접속하시면 몇가지 예제 테이블을 데이터베이스에 등록 합니다.

## 버전별 주요 업그레이드
v1.0
> MVC 패턴 적용

v1.4
> Bootstrap, Sweetalert, Animate of scroll 적용

v1.7
> PSR-4 적용

v1.8
> 전 프로세스 클래스화
> 템플릿 함수를 통해서 변수 관리

v1.9
> ckeditor 모듈 포함
> 범용적으로 사용 가능한 파일 업로드 클래스 적용
> 예제로 사용할 install 클래스 추가
    > /login - 로그인
    > /notice - 게시판형태 (ckeditor 및 파일 업로드 예제)
