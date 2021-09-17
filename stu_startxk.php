<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
if ((!isset($_SESSION['stu_name'])) || (!isset($_SESSION['stu_id']))) {
    header("Location:login.html");
    exit;
}
error_reporting(E_ERROR);
require_once('mysql_connect.php');
?>
<!DOCTYPE html>
<head>
    <title>学生成绩管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/style.css" rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" href="css/font.css" type="text/css"/>
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery2.0.3.min.js"></script>

</head>
<body>
<section id="container">
    <!--header start-->
    <header class="header fixed-top clearfix">
        <!--logo start-->
        <div class="brand">
            <a href="index_stu.php" class="logo">
                学生
            </a>
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars"></div>
            </div>
        </div>
        <!--logo end-->
        <div class="nav notify-row" id="top_menu">

        </div>
        <div class="top-nav clearfix">
            <ul class="nav pull-right top-menu">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img alt="" src="images/2.png">
                        <span class="username"><?php echo $_SESSION['stu_name']; ?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <li><a href="stu_xgmm.php"><i class="fa fa-cog"></i> 修改密码</a></li>
                        <li><a href="index_stu.php?action=logout"><i class="fa fa-key"></i> 注销</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end -->

            </ul>
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse">
            <!-- sidebar menu start-->
            <div class="leftside-navigation">
                <ul class="sidebar-menu" id="nav-accordion">
                    <li>
                        <a class="active" href="index_stu.php">
                            <i class="fa fa-dashboard"></i>
                            <span>首页</span>
                        </a>
                    </li>


                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-th"></i>
                            <span>基本信息</span>
                        </a>
                        <ul class="sub">
                            <li><a href="stu_selstu.php">查看基本信息</a></li>
                            <li><a href="stu_upstu.php">修改基本信息</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-tasks"></i>
                            <span>选课信息</span>
                        </a>
                        <ul class="sub">
                            <li><a href="stu_startxk.php">开始选课</a></li>
                            <li><a href="stu_yxkc.php">已选课程/退课</a></li>

                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-envelope"></i>
                            <span>成绩查询</span>
                        </a>
                        <ul class="sub">
                            <li><a href="stu_selcj.php">成绩查询</a></li>
                            <li><a href="stu_selxfj.php">学分绩查询</a></li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-glass"></i>
                            <span>统计信息</span>
                        </a>
                        <ul class="sub">
                            <li><a href="stu_yqdxf.php">已取得学分</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        选课
                    </div>
                    <div>
                        <form action='stu_startxk.php' method='post'>
                            请选择学年&nbsp;
                            <select name="val">
                                <option value="2018-2019_1">2018-2019第一学期</option>
                                <option value="2018-2019_2">2018-2019第二学期</option>
                                <option value="2019-2020_1">2019-2020第一学期</option>
                                <option value="2019-2020_2">2019-2020第二学期</option>
                                <option value="2020-2021_1">2020-2021第一学期</option>
                                <option value="2020-2021_2">2020-2021第二学期</option>
                                <option value="2021-2022_1">2021-2022第一学期</option>
                                <option value="2021-2022_2">2021-2022第二学期</option>
                            </select>
                            &nbsp;
                            <button type="submit" class="btn btn-info" name="selxn">查询</button>
                        </form>
                        <form action='stu_xkcl.php' method='post'>
                            <table class="table" ui-jq="footable" ui-options='{
        "paging": {
          "enabled": true
        },
        "filtering": {
          "enabled": true
        },
        "sorting": {
          "enabled": true
        }}'>

                                <thead>
                                <tr>
                                    <th data-breakpoints="xs">课程号</th>
                                    <th>课程名</th>
                                    <th>学分</th>
                                    <th>学年</th>
                                    <th>教师</th>
                                    <th>选课</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                error_reporting(E_ERROR);
                                $val = $_POST['val'];
                                $A = $_SESSION['stu_id'];
                                if (!$val) {
                                    $sql = "select course.*,teacher.* from course,teacher  where  course.teach_id=teacher.teach_id and course.course_id !=all(select course_id from grade where stuno='$A')";//只显示当前未选修的课程
                                } else {
                                    $sql = "select course.*,teacher.* from course,teacher  where  course.teach_id=teacher.teach_id and course.xktime='$val' and course.course_id !=all(select course_id from grade where stuno='$A')";
                                }

                                $res = mysqli_query($conn, $sql);
                                while ($arr = mysqli_fetch_assoc($res)) {
                                    echo "<tr data-expanded='true'>";
                                    echo "<td>" . $arr['course_id'] . "</td>";
                                    echo "<td>" . $arr['course_name'] . "</td>";
                                    echo "<td>" . $arr['credit'] . "</td>";
                                    echo "<td>" . $arr['xktime'] . "</td>";
                                    echo "<td>" . $arr['name'] . "</td>";
                                    echo "<td><input type='checkbox' name='chk[]' value='" . $arr['course_id'] . "' /></td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                            <center>
                                <button type="submit" class="btn btn-info" name="selkc">选课</button>&emsp;&emsp;
                                <button type="reset" class="btn btn-info">重置</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>

        </section>
        <!--main content end-->
        <!-- footer -->
        <div class="footer">
            <div class="wthree-copyright">
                <p><a>© 2020 学生成绩管理系统.Wei.Zhong.Lin. Reserved.</a></p>
            </div>
        </div>
    </section>
</section>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
<!-- calendar -->
<script type="text/javascript" src="js/monthly.js"></script>
<script type="text/javascript">
    $(window).load(function () {

        $('#mycalendar').monthly({
            mode: 'event',

        });

        $('#mycalendar2').monthly({
            mode: 'picker',
            target: '#mytarget',
            setWidth: '250px',
            startHidden: true,
            showTrigger: '#mytarget',
            stylePast: true,
            disablePast: true
        });

        switch (window.location.protocol) {
            case 'http:':
            case 'https:':
                // running on a server, should be good.
                break;
        }

    });
</script>
</body>
</html>
