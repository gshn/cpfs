<header id="page-header">
    <h5 class="title">Dashboard</h5>
    <ul class="category">
        <li class="slide-bar">
            <a href="/">Home</a>
        </li>
        <li>
            <a href="/dashboard">Dashboard</a>
        </li>
    </ul>
</header>
<div id="dashboard" class="container-fluid">
    <div class="row main-row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="user-profile-grid bg-primary text-white">
                    <div class="user-photo text-primary">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="user-profile">
                        <h5>이름님</h5>
                        <h6>아이디</h6>
                        <p><small>일반사용자</small></p>
                    </div>
                </div>
                <div class="user-summary-grid">
                    <ul class="user-summary-list flex-list">
                        <li>
                            <div class="section-padding">
                                <h6>1,340건</h6>
                                <p>데이터</p>
                            </div>
                        </li>
                        <li>
                            <div class="section-padding">
                                <h6>521MB</h6>
                                <p>전송량</p>
                            </div>
                        </li>
                        <li>
                            <div class="section-padding">
                                <h6>0</h6>
                                <p>포인트</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 col">
            <div class="data-grid bg-white">
                <div class="data-icon text-center bg-info text-white">
                    <i class="fa fa-fw fa-file-archive-o"></i> 파일 73건
                </div>
                <div class="data-content section-padding relative">
                    <h6>미르치과병의원 <small>2017.07.15</small></h6>
                    <p>315MB</p>
                    <div class="btn-more">
                        <a href="" class="btn-danger"><i class="fa fa-share"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 col">
            <div class="data-grid bg-white">
                <div class="data-icon text-center bg-warning text-white">
                    <i class="fa fa-fw fa-file-archive-o"></i> 파일 73건
                </div>
                <div class="data-content section-padding relative">
                    <h6>미르치과병의원 <small>2017.07.15</small></h6>
                    <p>315MB</p>
                    <div class="btn-more">
                        <a href="" class="btn-primary"><i class="fa fa-share"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 col">
            <section class="badge-div bg-white">
                <div class="badge-icon text-white bg-warning">
                    <i class="fa fa-cloud"></i>
                </div>
                <div class="badge-content">
                    <h3>540GB</h3>
                    <p>클라우드 데이터</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 col">
            <section class="badge-div bg-white">
                <div class="badge-icon text-white bg-success">
                    <i class="fa fa-cloud-upload"></i>
                </div>
                <div class="badge-content">
                    <h3>2.5TB</h3>
                    <p>이번 달 업로드</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 col">
            <section class="badge-div bg-white">
                <div class="badge-icon text-white bg-info">
                    <i class="fa fa-cloud-download"></i>
                </div>
                <div class="badge-content">
                    <h3>7.8TB</h3>
                    <p>이번 달 다운로드</p>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 col">
            <section class="badge-div bg-white">
                <div class="badge-icon bg-primary">
                    <i class="fa fa-files-o"></i>
                </div>
                <div class="badge-content">
                    <h3>540,445</h3>
                    <p>저장된 파일</p>
                </div>
            </section>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid bg-danger text-white">
                    <h6 class="pad-bottom-half">일일 저장된 데이터 (GB)</h6>
                    <data-chart :height="226"></data-chart>
                </div>
                <section class="chart-summary relative">
                    <h3>184GB</h3>
                    <p>최근 10일간 저장된 데이터</p>
                    <div class="btn-more">
                        <a href="/data" class="btn-warning"><i class="fa fa-plus"></i></a>
                    </div>
                </section>
                <div class="row main-row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col border-right">
                        <section class="latest-data-type">
                            <div class="latest-data-icon text-info">
                                <i class="fa fa-file-archive-o"></i>
                            </div>
                            <div class="latest-data-content">
                                <h5>4,398건</h5>
                                <p>CT파일</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col">
                        <section class="latest-data-type">
                            <div class="latest-data-icon text-info">
                                <i class="fa fa-file-image-o"></i>
                            </div>
                            <div class="latest-data-content">
                                <h5>14,284건</h5>
                                <p>이미지파일</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col border-right">
                        <section class="latest-data-type">
                            <div class="latest-data-icon text-info">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="latest-data-content">
                                <h5>13,245건</h5>
                                <p>문서파일</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col">
                        <section class="latest-data-type pad-bottom">
                            <div class="latest-data-icon text-info">
                                <i class="fa fa-file-o"></i>
                            </div>
                            <div class="latest-data-content">
                                <h5>5,657건</h5>
                                <p>기타파일</p>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">타입별 과금 금액 (원)</h6>
                    <invoice-chart :height="373"></invoice-chart>
                </div>
                <div class="row main-row">
                    <div class="col-xs-12">
                        <section class="invoice-total relative">
                            <h3>7,324,000원</h3>
                            <p>이번 달 과금 금액</p>
                            <div class="btn-more">
                                <a href="/invoice" class="btn-primary"><i class="fa fa-plus"></i></a>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">최근 상담 내역</h6>
                </div>
                <section class="bg-offwhite section-padding overflow-hidden">
                    <div class="contact-summary-date">
                        <h5><?php echo date('Y년 m월')?></h5>
                        <p>가입신청 및 일반상담</p>
                    </div>
                    <div class="contact-summary-result">
                        <h2><a href="/contact">7건</a></h2>
                    </div>
                </section>
                <div class="table-responsive" style="height: 416px">
                    <table class="table table-custom">
                    <tr>
                        <th class="text-center">#</th>
                        <th>이름</th>
                        <th>상담상태</th>
                        <th>신청일</th>
                        <th>연락처</th>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>미르치과</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.18</td>
                        <td><a href="">hheo@cozmoworks.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>김소훈</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.17</td>
                        <td><a href="">010-6456-4580</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>라온치과병의원</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.14</td>
                        <td><a href="">010-6245-6538</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>누렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.13</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>파르치과</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.12</td>
                        <td><a href="">gilynh@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td>솔르치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.11</td>
                        <td><a href="">gs126997@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td>뻐드렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.09</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="calendar">
                    <div class="calendar-today-grid">
                        <div class="calendar-today-top">
                            <h1><?php echo date('d')?></h1>
                            <h6><?php echo get_yoil(YMD)?>요일</h6>
                            <hr>
                            <p><?php echo date('Y년 m월')?></p>
                        </div>
                        <div class="calendar-today-bottom">
                            <h6>356,000원 <small>12:30</small></h6>
                            <p>메모 (외 3건)</p>
                        </div>
                    </div>
                    <div class="calendar-grid bg-primary text-white">
                        <div class="calendar-header relative">
                            <h1>July</h1>
                            <div class="btn-more">
                                <a href="" class="text-primary"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="calendar-body">
                            <header class="week-row">
                                <div class="week-col">
                                    SUN
                                </div>
                                <div class="week-col">
                                    MON
                                </div>
                                <div class="week-col">
                                    TUE
                                </div>
                                <div class="week-col">
                                    WED
                                </div>
                                <div class="week-col">
                                    THU
                                </div>
                                <div class="week-col">
                                    FRI
                                </div>
                                <div class="week-col">
                                    SAT
                                </div>
                            </header>
                            <?php
                            $day = -5;
                            for ($i = 0; $i < 6; $i += 1) { ?>
                            <div class="week-row">
                                <?php for ($j = 0; $j < 7; $j += 1) { ?>
                                <div class="week-col <?php echo $day == date('d') ? 'today text-primary bg-white' : ''?>">
                                    <?php echo ($day >= 1 && $day <= 31) ? $day : '&nbsp;'?>
                                </div>
                                <?php
                                $day += 1;
                            } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">업-다운로드 (GB)</h6>
                    <up-down-chart :height="150"></up-down-chart>
                </div>
            </section>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">주간 회원 가입자 (명)</h6>
                    <member-chart :height="150"></member-chart>
                </div>
            </section>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">방문자 카운트 (명)</h6>
                    <visit-chart :height="150"></member-chart>
                </div>
            </section>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">신규 환자 리스트</h6>
                </div>
                <div class="table-responsive" style="height: 416px">
                    <table class="table table-custom">
                    <tr>
                        <th class="text-center">#</th>
                        <th>이름</th>
                        <th>상담상태</th>
                        <th>신청일</th>
                        <th>연락처</th>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>미르치과</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.18</td>
                        <td><a href="">hheo@cozmoworks.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>김소훈</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.17</td>
                        <td><a href="">010-6456-4580</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>라온치과병의원</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.14</td>
                        <td><a href="">010-6245-6538</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>누렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.13</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>파르치과</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.12</td>
                        <td><a href="">gilynh@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td>솔르치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.11</td>
                        <td><a href="">gs126997@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td>뻐드렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.09</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col">
            <section class="bg-white">
                <div class="chart-grid">
                    <h6 class="pad-bottom-half">신규 병의원 리스트</h6>
                </div>
                <div class="table-responsive" style="height: 416px">
                    <table class="table table-custom">
                    <tr>
                        <th class="text-center">#</th>
                        <th>이름</th>
                        <th>상담상태</th>
                        <th>신청일</th>
                        <th>연락처</th>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td>미르치과</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.18</td>
                        <td><a href="">hheo@cozmoworks.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>김소훈</td>
                        <td><span class="label label-danger">가입신청</span></th>
                        <td><?php echo date('Y.m')?>.17</td>
                        <td><a href="">010-6456-4580</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>라온치과병의원</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.14</td>
                        <td><a href="">010-6245-6538</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>누렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.13</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>파르치과</td>
                        <td><span class="label label-info">일반상담</span></th>
                        <td><?php echo date('Y.m')?>.12</td>
                        <td><a href="">gilynh@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td>솔르치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.11</td>
                        <td><a href="">gs126997@naver.com</a></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td>뻐드렁니치과</td>
                        <td><span class="label label-success">상담완료</span></th>
                        <td><?php echo date('Y.m')?>.09</td>
                        <td><a href="">gs@gs.hn</a></td>
                    </tr>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
