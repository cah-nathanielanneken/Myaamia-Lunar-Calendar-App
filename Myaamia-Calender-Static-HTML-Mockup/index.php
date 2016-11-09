<!DOCTYPE html>
<html lang="en">
<head>
    <title>Myaamia Calender</title>
    <script src="js/jquery.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="CalendarCSS.css">
    <script src="js/bootstrap.js"></script>

</head>
<body>
<!--Bootstrap Navbar-->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid ">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!--Probably should contain logo instead of text-->
                    <a class="navbar-brand" href="index.html">Myaamia Calendar</a>
                </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-right">
                    <li>
                        <a href="#"><img src="res/images/search_white.png" class="icon-top"> Search</a>
                    </li>
                    <li>
                        <a href="#"><img src="res/images/browse_white.png" class="icon-top"> Browse</a>
                    </li>
                    <li>
                        <a href="#"><img src="res/images/feedback_white.png" class="icon-top"> Feedback</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!--Ribbon artwork-->
    <div class="nav-ribbon"></div>
    <div class="side-ribbon"></div>

    <br><br><br><br><br>

    <div class="mobile-ribbon"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-sm-offset-2 col-md-6 col-sm-6">
                <h1>Welcome To The Myaamia Calendar!</h1>
                <h2 class="myaamia-yellow">Sub Heading (if needed)</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-sm-offset-2 col-md-10 col-sm-10 parchment">
                <table id="calendar" align="center">
		<?php
			include('LunarMonth.php');
			$curDate = date('Y-m-d');
			$lunarMonth = new LunarMonth($curDate);
			print "<tr><td colspan='7'><h2>{$lunarMonth->myaamiaName}</h2><div><h3>{$lunarMonth->englishName}</h3></div></td></tr>";
		?>
		<tr> <!-- Day Names -->
			<td>eelaamini-kii&#353;ikahki</td>
			<td>nkotakone</td>
			<td>niis&#353;akone</td>
			<td>nihsokone</td>
			<td>niiyakone</td>
			<td>yaalanokone</td>
			<td>kaakaathsokone</td>
		</tr>
		<tr>
                        <?php
				$daysOfWeek = array('eelaamini-kiišikahki','nkotakone','niisšakone','nihsokone','niiyakone','yaalanokone','kaakaathsokone');
				$i;
				for ($i = 0; $i < 7; $i++) {
					if ($lunarMonth->daysInMonth[1]->myaamiaName != $daysOfWeek[$i]) {
						print "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
					} else {
						break;
					}
				}
				$dateIndex = 1;
				for ($week = 0; $week < 5; $week++) {
					for (; $i < 7; $i++) {
						if ($dateIndex > count($lunarMonth->daysInMonth)) {
							print "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
						} else {
							$gregDate = date('m/d', strtotime($lunarMonth->daysInMonth[$dateIndex]->gregorianDate));
							print "<td><div class='miami-label'>{$lunarMonth->daysInMonth[$dateIndex]->dayOfLunarMonth}</div><div class='gregDate'>{$gregDate}</div></td>";
							$dateIndex++;
						}
					}
					print '</tr>';
					if ($week != 4) {
						print '<tr>';
					}
					$i = 0;
				}
                        ?>
	</table>

            </div>
        </div>
    </div>
    </div>

</body>
</html>
