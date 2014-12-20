<?php
/**
 * Template for displaying all pages
 *
 * This is a page to display all SendGrid recipient
 * lists and allow the user to view information about
 * the lists. Modified from page.php from
 * Twenty Eleven 1.0. Written by Michael Meluso.
 * www.michaelmeluso.com
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

                    
					<?php
                    require "sendgrid-get-lists.php";
                    // SendGrid list custom code
                    // SendGrid List Info 
                    if (empty($_POST)) {
                        echo "<h1 class=\"entry-title\">Recipient List Overview</h1>";
                        echo "<form id=\"form_get_info\" method=\"post\" action=\"".get_permalink()."\">";
                        echo "<table class=\"recip-table\">";
                        echo "<tr class = \"recip-table-top\">";
                        echo "<th><strong>Recipient List</strong></th>";
                        echo "<th><strong>Number of emails</strong></th>";
                        echo "<th><strong>Link to stats</strong></th>";
                        echo "</tr>";
                        $i = 0;
                        $lists = getLists(null);
                        foreach ($lists as $value) {
                            $value = substr($value, 0, strlen($value)-1);
                            $valueF = str_replace(' ', '-', $value);
                            $count = countLists($value);
                            $url = getStatURL($value);
                            echo "<tr class=\"recip-table-tr\">";
                            echo "<td><input type=\"submit\" name=\"$valueF\" value=\"$value\">";
                            echo "<td class=\"recip-table-td\">$count</td>";
                            echo "<td class=\"recip-table-td\"><a href = $url target=\"_blank\">Stats</a></td>";
                            echo "</tr>";
                            $i = $i + 1;
                        }
                        echo "</table>";
                        echo "</form>";
                    } else {
                        foreach($_POST as $name => $value) {
                            $valueF = str_replace('-', ' ', $value);
                            echo "<h1 class=\"entry-title\">Overview for List $value</h1>";
                            echo "Number of emails: " . countLists($value) . "<br>";
                            echo "Emails:<br>";
                            $emails = getEmails($value, null);
                            foreach ($emails as $entry) {
                                echo $entry . "<br>";
                            }
                        }
                    }
                    echo "<br><a href=\"http://www.solec.org/index.php/sendgrid-manager/\">Back to SendGrid Manager</a>";
                    ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>