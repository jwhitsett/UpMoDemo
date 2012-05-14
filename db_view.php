<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<TITLE>UpMo Demo Code</TITLE>
<META content="text/html; charset=UTF-8" http-equiv="Content-Type">
	<link media="all" type="text/css" href="http://www.whitsett.net/upmodemo/styles/whitsett.css" rel="stylesheet">
</HEAD>
<?php 
	function printRow($row) {
		print("<tr><td>$row->fName</td><td>$row->lName</td><td>$row->headline</td></tr>");
	}
?>
<BODY class="gradient-bg">
	<div id="mainlayout" class="nospace">
		<div id="wrapper" class="nospace">
			<p class="title-black">Linkedin Users Who Have Visited This Application</p>
			<table class="tabularData">
				<thead>
					<th>First Name</th><th>Last Name</th><th>Job Title</th>
				</thead>
				<tbody>

				<?php
					foreach ($query->result() as $row) {
					printRow($row);
					};
				?>
				
				</tbody>
			</table>	
		</div>
	</div>
</BODY>
</HTML>