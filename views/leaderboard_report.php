<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome Ben Branyon's Kixeye Report</title>
<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

</style>
</head>
<body>

<h1>Leaderboard Report</h1>

<ul>
	<li>Total Players: <?php echo $total_players;?></li>
	<li>Players Today: <?php echo $total_players_today;?></li>
	<li>Top 10 Players:
		<ul>
				<?php foreach($top_ten_players as $player):?>
					<li>Name: <?php echo $player->first_name;?> <?php echo $player->last_name; echo '<br/>';?> Score: <?php echo $player->score;?></li>
				<?php endforeach;?>
		</ul> 
	</li>
	<li>Top 10 Players who improved their score: 
		<ul>
				<?php $i = 1;?>
				<?php while($i <= 10):?>
					<li><?php echo $top_ten_players_improved[$i]['first_name'];?> <?php echo $top_ten_players_improved[$i]['last_name']; echo '<br>';?> Score: <?php echo $top_ten_players_improved[$i]['score'];?></li>
					<?php $i++;?>
				<?php endwhile;?>
		</ul>
	</li>
</ul>
</body>
</html>