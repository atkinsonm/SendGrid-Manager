<?php
/**
 * Template for displaying all pages
 *
 * This is a page to display all SendGrid recipient
 * lists and allow the user to add email addresses to
 * one or multiple lists. Modified from page.php from
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
                    require "sendgrid-add-list.php";
                    require "sendgrid-get-lists.php";
                    // SendGrid Add Recipient List
                    if (empty($_POST)) {
                        echo "<h1 class=\"entry-title\">Add Recipient List</h1>";
                        echo "<form id=\"form_add_list\" method=\"post\" action=\"".get_permalink()."\">";
                        echo "<input type=\"text\" name=\"listName\" placeholder=\"Name of list\"><br>";
                        echo "<input type=\"submit\" value=\"Add\">";
                        echo "</form>";
                    } else {
                        $lists = array();
                        foreach($_POST as $name => $value) {
                            //If the data begins with "listName", use it.
                            if ($name == "listName") {
                                $resultMsg = addList($value);
                                echo $resultMsg . "<br>";
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