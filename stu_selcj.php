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
    <link href="css/style.css" rel='stylesheet' type='text/css'>
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
                        查看成绩
                    </div>
                    <div>
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
                                <th>成绩</th>
                                <th>排名</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            error_reporting(E_ERROR);
                            $A = $_SESSION['stu_id'];
                            $sql = "select course.*,teacher.* from course,teacher  where  course.teach_id=teacher.teach_id and course.course_id =any(select course_id from grade where stuno='$A' and flag='2')";
                            $res = mysqli_query($conn, $sql);
                            $yx = 0;
                            $lh = 0;
                            $z = 0;
                            $jg = 0;
                            $bjg = 0;
                            if (mysqli_num_rows($res)) {
                                while ($arr = mysqli_fetch_assoc($res)) {
                                    echo "<tr data-expanded='true'>";
                                    echo "<td>" . $arr['course_id'] . "</td>";
                                    echo "<td>" . $arr['course_name'] . "</td>";
                                    echo "<td>" . $arr['credit'] . "</td>";
                                    echo "<td>" . $arr['xktime'] . "</td>";
                                    $course = $arr['course_id'];
                                    $sql_pm = "select * from grade where course_id='$course'";

                                    //排名查询

                                    $r = mysqli_query($conn, $sql_pm);
                                    while ($result = mysqli_fetch_array($r)) {
                                        $grade = $result['grade'];
                                        //找出本课程成绩大于该学生的人数
                                        $pm = "select * from grade where grade>'$grade' and course_id='$course'";
                                        $pm_query = mysqli_query($conn, $pm);
                                        $rank_row = mysqli_num_rows($pm_query);
                                        $rank = $rank_row + 1;
                                        if ($result['stuno'] == $A) {
                                            if ($result['grade'] >= 90)
                                                $yx++;
                                            else if ($result['grade'] >= 80)
                                                $lh++;
                                            else if ($result['grade'] >= 70)
                                                $z++;
                                            else if ($result['grade'] >= 60)
                                                $jg++;
                                            else
                                                $bjg++;
                                            echo "<td>" . $result['grade'] . "</td>";
                                            echo "<td>" . $rank . "</td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td>无结课科目！</td></tr>";
                            }
                            echo "<tr><td>成绩分布:</td></tr>";
                            echo "<tr><td>优(90-100)</td><td>良(80-90)</td><td>中(70-80)</td><td>及格(60-70)</td><td>不及格(60以下)</td></tr>";
                            echo "<tr><td>" . $yx . "</td><td>" . $lh . "</td><td>" . $z . "</td><td>" . $jg . "</td><td>" . $bjg . "</td></tr>";
                            ?>
                            </tbody>
                        </table>
                        <a href="stu_putcj.php?flag=<?php echo $A; ?>" style="float:right;font-size:15px">下载成绩</a>
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
</body>
</html>
<?php
if ($_GET['action'] == "logout") {
    unset($_SESSION['stu_name']);
    unset($_SESSION['stu_id']);
    echo "<script> alert('注销成功');</script>";
    echo "<script type='text/javascript'>" . "location.href='" . "login.html" . "'" . "</script>";
}
?>
<?php
if ($_GET['action'] == "tk") {
    error_reporting(E_ERROR);
    $stuno = $_SESSION['stu_id'];

    foreach ($_POST['chk'] as $val) {
        $sql = "delete from grade where stuno='$stuno' and course_id='$val'";
        $res = mysqli_query($conn, $sql);
    }
    if ($res) {
        echo "<script> alert('退课成功');</script>";
        echo "<script type='text/javascript'>" . "location.href='" . "stu_yxkc.php" . "'" . "</script>";
    } else {
        echo "<script> alert('退课失败');</script>";
        echo "<script> history.go(-1);</script>";
    }
}
?>
