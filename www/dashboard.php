<?php
session_start();
$_SESSION['active'] = true;

#Title
$page_title = "DashBoard";

#Date...
$current_year = 2017;


 include 'includes/db.php';

 include 'includes/function.php';

 include 'includes/header.php';
?>
	<div class="wrapper">
		<div id="stream">
			<table id="tab">
				<thead>
					<tr>
						<th>post title</th>
						<th>post author</th>
						<th>date created</th>
						<th>edit</th>
						<th>delete</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<!-- <td>the knowledge gap</td>
						<td>maja</td>
						<td>January, 10</td>
						<td><a href="#">edit</a></td>
						<td><a href="#">delete</a></td> -->
						<?php  $view = seeProducts($conn); echo $view; ?>

					</tr>
          		</tbody>

			</table>
		</div>

		<div class="paginated">
			<a href="#">1</a>
			<a href="#">2</a>
			<span>3</span>
			<a href="#">2</a>
		</div>
	</div>

	<section class="foot">
		<div>
			<p>&copy; 2016;
		</div>
	</section>
</body>
</html>
