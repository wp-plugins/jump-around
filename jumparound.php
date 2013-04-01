<?php
/*
	Plugin Name: Jump Around
	Plugin URI: http://papercaves.com/wordpress-plugins/
	Description: Navigate posts by pressing keys on the keyboard.
	Version: 1.4
	Author: Matthew Trevino
	Author URI: http://papercaves.com
	License: A "Slug" license name e.g. GPL2

	Copyright 2013  Matthew Trevino  (boyevul@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


	Contents -
		JA-000 Check for (and enqueue if not present) jQuery
		JA-001 Options settings validation
		JA-002 The form for the options page.	
		JA-003 The options page.
		JA-004 Output appropriate script in footer.


	// Thanks to jitter on stackoverflow for the window.location.hash tidbit (http://stackoverflow.com/questions/1939041/change-hash-without-reload-in-jquery)
	// Thanks to mVChr for the scroll-to (via keys) (http://stackoverflow.com/questions/13694277/scroll-to-next-div-using-arrow-keys)
		
*/


// JA-000
// Check for (and enqueue if not present) jQuery
	if(wp_script_is('jquery')) {
	
	} else {
		wp_enqueue_script('jquery');
	}

	register_activation_hook( __FILE__, "jump_around_install" );
	register_deactivation_hook( __FILE__, "jump_around_uninstall" );

	function jump_around_install() {
		
		// core settings
		add_option("jump_around_0","post","Post wrap");
		add_option("jump_around_1","entry-title","Link wrap");
		add_option("jump_around_2","previous-link","Previous link");
		add_option("jump_around_3","next-link","Next link");
		add_option("jump_around_4","65","Previous");
		add_option("jump_around_5","83","View");
		add_option("jump_around_6","68","Next");
		add_option("jump_around_7","90","Older posts");
		add_option("jump_around_8","88","Newer posts");
		add_option("jump_around_delete_on_deactivate","no","Delete on deactivate?");
	}

	
// Are we deleting information from the database?
// Check to make sure that the option to delete is set to "yes" before we delete the information.	
	function jump_around_uninstall() {
		if ( get_option("jump_around_delete_on_deactivate") === "yes") {
			delete_option("jump_around_0");
			delete_option("jump_around_1");
			delete_option("jump_around_2");
			delete_option("jump_around_3");
			delete_option("jump_around_4");
			delete_option("jump_around_5");
			delete_option("jump_around_6");
			delete_option("jump_around_7");
			delete_option("jump_around_8");			
			delete_option("jump_around_delete_on_deactivate");
		} else { }
		
	}

// JA-001
// Options settings validation
// Take the values passed from the options page and insert them into the database for saving.
	function update_JA() {
		$jump_around_was_updated = false;
		
		if (
				$_REQUEST["jump_around_0"] ||
				$_REQUEST["jump_around_1"] ||
				$_REQUEST["jump_around_2"] ||
				$_REQUEST["jump_around_3"] ||
				$_REQUEST["jump_around_4"] ||
				$_REQUEST["jump_around_5"] ||
				$_REQUEST["jump_around_6"] ||
				$_REQUEST["jump_around_7"] ||
				$_REQUEST["jump_around_8"] ||				
				$_REQUEST["jump_around_delete_on_deactivate"]
				
			) {
			
			update_option("jump_around_0",$_REQUEST["jump_around_0"]);
			update_option("jump_around_1",$_REQUEST["jump_around_1"]);
			update_option("jump_around_2",$_REQUEST["jump_around_2"]);
			update_option("jump_around_3",$_REQUEST["jump_around_3"]);
			update_option("jump_around_4",$_REQUEST["jump_around_4"]);
			update_option("jump_around_5",$_REQUEST["jump_around_5"]);
			update_option("jump_around_6",$_REQUEST["jump_around_6"]);
			update_option("jump_around_7",$_REQUEST["jump_around_7"]);
			update_option("jump_around_8",$_REQUEST["jump_around_8"]);
			update_option("jump_around_delete_on_deactivate",$_REQUEST["jump_around_delete_on_deactivate"]);
			$jump_around_was_updated = true;
			
		}
		
		// Options were updated - tell the user about it.
		if ($jump_around_was_updated) { 
			echo "<p>Options saved.</p>"; 
		}
	}

// JA-002
// The form for the options page.	

	if (is_admin() ) {
	
		wp_register_style( 'JAStylesheet', plugins_url('style.css', __FILE__), '1' );
		wp_enqueue_style( 'JAStylesheet' );
		
	}


	function print_JA_form() {
	
		$default_jump_around_0 = get_option("jump_around_0");
		$default_jump_around_1 = get_option("jump_around_1");
		$default_jump_around_2 = get_option("jump_around_2");
		$default_jump_around_3 = get_option("jump_around_3");
		$default_jump_around_4 = get_option("jump_around_4");
		$default_jump_around_5 = get_option("jump_around_5");
		$default_jump_around_6 = get_option("jump_around_6");
		$default_jump_around_7 = get_option("jump_around_7");
		$default_jump_around_8 = get_option("jump_around_8");
		$default_jump_around_delete_on_deactivate = get_option("jump_around_delete_on_deactivate");
		
		echo "
		<form method=\"post\">

		<label for=\"jump_around_0\">Post container class:
		<input type=\"text\" name=\"jump_around_0\" value=\"",$default_jump_around_0,"\" />
		</label><br />
		
		<label for=\"jump_around_1\">Post permalink class:
		<input type=\"text\" name=\"jump_around_1\" value=\"",$default_jump_around_1,"\" />
		</label><br />
		
		<label for=\"jump_around_2\">Previous posts link wrapper:
		<input type=\"text\" name=\"jump_around_2\" value=\"",$default_jump_around_2,"\" />
		</label><br />
		
		<label for=\"jump_around_3\">Next posts link wrapper:
		<input type=\"text\" name=\"jump_around_3\" value=\"",$default_jump_around_3,"\" />
		</label><br />
		
		<hr />
		
		<label for=\"jump_around_4\">Previous key:
		<select name=\"jump_around_4\">
		<option value=\"65\"";if ($default_jump_around_4 == "65") { echo " selected=\"selected\""; } echo ">a</option>
		<option value=\"66\"";if ($default_jump_around_4 == "66") { echo " selected=\"selected\""; } echo ">b</option>
		<option value=\"67\"";if ($default_jump_around_4 == "67") { echo " selected=\"selected\""; } echo ">c</option>
		<option value=\"68\"";if ($default_jump_around_4 == "68") { echo " selected=\"selected\""; } echo ">d</option>
		<option value=\"69\"";if ($default_jump_around_4 == "69") { echo " selected=\"selected\""; } echo ">e</option>
		<option value=\"70\"";if ($default_jump_around_4 == "70") { echo " selected=\"selected\""; } echo ">f</option>
		<option value=\"71\"";if ($default_jump_around_4 == "71") { echo " selected=\"selected\""; } echo ">g</option>
		<option value=\"72\"";if ($default_jump_around_4 == "72") { echo " selected=\"selected\""; } echo ">h</option>
		<option value=\"73\"";if ($default_jump_around_4 == "73") { echo " selected=\"selected\""; } echo ">i</option>
		<option value=\"74\"";if ($default_jump_around_4 == "74") { echo " selected=\"selected\""; } echo ">j</option>
		<option value=\"75\"";if ($default_jump_around_4 == "75") { echo " selected=\"selected\""; } echo ">k</option>
		<option value=\"76\"";if ($default_jump_around_4 == "76") { echo " selected=\"selected\""; } echo ">l</option>
		<option value=\"77\"";if ($default_jump_around_4 == "77") { echo " selected=\"selected\""; } echo ">m</option>
		<option value=\"78\"";if ($default_jump_around_4 == "78") { echo " selected=\"selected\""; } echo ">n</option>
		<option value=\"79\"";if ($default_jump_around_4 == "79") { echo " selected=\"selected\""; } echo ">o</option>
		<option value=\"80\"";if ($default_jump_around_4 == "80") { echo " selected=\"selected\""; } echo ">p</option>
		<option value=\"81\"";if ($default_jump_around_4 == "81") { echo " selected=\"selected\""; } echo ">q</option>
		<option value=\"82\"";if ($default_jump_around_4 == "82") { echo " selected=\"selected\""; } echo ">r</option>
		<option value=\"83\"";if ($default_jump_around_4 == "83") { echo " selected=\"selected\""; } echo ">s</option>
		<option value=\"84\"";if ($default_jump_around_4 == "84") { echo " selected=\"selected\""; } echo ">t</option>
		<option value=\"85\"";if ($default_jump_around_4 == "85") { echo " selected=\"selected\""; } echo ">u</option>
		<option value=\"86\"";if ($default_jump_around_4 == "86") { echo " selected=\"selected\""; } echo ">v</option>
		<option value=\"87\"";if ($default_jump_around_4 == "87") { echo " selected=\"selected\""; } echo ">w</option>
		<option value=\"88\"";if ($default_jump_around_4 == "88") { echo " selected=\"selected\""; } echo ">x</option>
		<option value=\"89\"";if ($default_jump_around_4 == "89") { echo " selected=\"selected\""; } echo ">y</option>
		<option value=\"90\"";if ($default_jump_around_4 == "90") { echo " selected=\"selected\""; } echo ">z</option>
		<option value=\"48\"";if ($default_jump_around_4 == "48") { echo " selected=\"selected\""; } echo ">0</option>
		<option value=\"49\"";if ($default_jump_around_4 == "49") { echo " selected=\"selected\""; } echo ">1</option>
		<option value=\"50\"";if ($default_jump_around_4 == "50") { echo " selected=\"selected\""; } echo ">2</option>
		<option value=\"51\"";if ($default_jump_around_4 == "51") { echo " selected=\"selected\""; } echo ">3</option>
		<option value=\"52\"";if ($default_jump_around_4 == "52") { echo " selected=\"selected\""; } echo ">4</option>
		<option value=\"53\"";if ($default_jump_around_4 == "53") { echo " selected=\"selected\""; } echo ">5</option>
		<option value=\"54\"";if ($default_jump_around_4 == "54") { echo " selected=\"selected\""; } echo ">6</option>
		<option value=\"55\"";if ($default_jump_around_4 == "55") { echo " selected=\"selected\""; } echo ">7</option>
		<option value=\"56\"";if ($default_jump_around_4 == "56") { echo " selected=\"selected\""; } echo ">8</option>
		<option value=\"57\"";if ($default_jump_around_4 == "57") { echo " selected=\"selected\""; } echo ">9</option>
		<option value=\"37\"";if ($default_jump_around_4 == "37") { echo " selected=\"selected\""; } echo ">left arrow</option>
		<option value=\"38\"";if ($default_jump_around_4 == "38") { echo " selected=\"selected\""; } echo ">up arrow</option>
		<option value=\"39\"";if ($default_jump_around_4 == "39") { echo " selected=\"selected\""; } echo ">right arrow</option>
		<option value=\"40\"";if ($default_jump_around_4 == "40") { echo " selected=\"selected\""; } echo ">down arrow</option>
		</select>
		</label><br />
	
		<label for=\"jump_around_5\">Open currently selected key:
		<select name=\"jump_around_5\">
		<option value=\"65\"";if ($default_jump_around_5 == "65") { echo " selected=\"selected\""; } echo ">a</option>
		<option value=\"66\"";if ($default_jump_around_5 == "66") { echo " selected=\"selected\""; } echo ">b</option>
		<option value=\"67\"";if ($default_jump_around_5 == "67") { echo " selected=\"selected\""; } echo ">c</option>
		<option value=\"68\"";if ($default_jump_around_5 == "68") { echo " selected=\"selected\""; } echo ">d</option>
		<option value=\"69\"";if ($default_jump_around_5 == "69") { echo " selected=\"selected\""; } echo ">e</option>
		<option value=\"70\"";if ($default_jump_around_5 == "70") { echo " selected=\"selected\""; } echo ">f</option>
		<option value=\"71\"";if ($default_jump_around_5 == "71") { echo " selected=\"selected\""; } echo ">g</option>
		<option value=\"72\"";if ($default_jump_around_5 == "72") { echo " selected=\"selected\""; } echo ">h</option>
		<option value=\"73\"";if ($default_jump_around_5 == "73") { echo " selected=\"selected\""; } echo ">i</option>
		<option value=\"74\"";if ($default_jump_around_5 == "74") { echo " selected=\"selected\""; } echo ">j</option>
		<option value=\"75\"";if ($default_jump_around_5 == "75") { echo " selected=\"selected\""; } echo ">k</option>
		<option value=\"76\"";if ($default_jump_around_5 == "76") { echo " selected=\"selected\""; } echo ">l</option>
		<option value=\"77\"";if ($default_jump_around_5 == "77") { echo " selected=\"selected\""; } echo ">m</option>
		<option value=\"78\"";if ($default_jump_around_5 == "78") { echo " selected=\"selected\""; } echo ">n</option>
		<option value=\"79\"";if ($default_jump_around_5 == "79") { echo " selected=\"selected\""; } echo ">o</option>
		<option value=\"80\"";if ($default_jump_around_5 == "80") { echo " selected=\"selected\""; } echo ">p</option>
		<option value=\"81\"";if ($default_jump_around_5 == "81") { echo " selected=\"selected\""; } echo ">q</option>
		<option value=\"82\"";if ($default_jump_around_5 == "82") { echo " selected=\"selected\""; } echo ">r</option>
		<option value=\"83\"";if ($default_jump_around_5 == "83") { echo " selected=\"selected\""; } echo ">s</option>
		<option value=\"84\"";if ($default_jump_around_5 == "84") { echo " selected=\"selected\""; } echo ">t</option>
		<option value=\"85\"";if ($default_jump_around_5 == "85") { echo " selected=\"selected\""; } echo ">u</option>
		<option value=\"86\"";if ($default_jump_around_5 == "86") { echo " selected=\"selected\""; } echo ">v</option>
		<option value=\"87\"";if ($default_jump_around_5 == "87") { echo " selected=\"selected\""; } echo ">w</option>
		<option value=\"88\"";if ($default_jump_around_5 == "88") { echo " selected=\"selected\""; } echo ">x</option>
		<option value=\"89\"";if ($default_jump_around_5 == "89") { echo " selected=\"selected\""; } echo ">y</option>
		<option value=\"90\"";if ($default_jump_around_5 == "90") { echo " selected=\"selected\""; } echo ">z</option>
		<option value=\"48\"";if ($default_jump_around_5 == "48") { echo " selected=\"selected\""; } echo ">0</option>
		<option value=\"49\"";if ($default_jump_around_5 == "49") { echo " selected=\"selected\""; } echo ">1</option>
		<option value=\"50\"";if ($default_jump_around_5 == "50") { echo " selected=\"selected\""; } echo ">2</option>
		<option value=\"51\"";if ($default_jump_around_5 == "51") { echo " selected=\"selected\""; } echo ">3</option>
		<option value=\"52\"";if ($default_jump_around_5 == "52") { echo " selected=\"selected\""; } echo ">4</option>
		<option value=\"53\"";if ($default_jump_around_5 == "53") { echo " selected=\"selected\""; } echo ">5</option>
		<option value=\"54\"";if ($default_jump_around_5 == "54") { echo " selected=\"selected\""; } echo ">6</option>
		<option value=\"55\"";if ($default_jump_around_5 == "55") { echo " selected=\"selected\""; } echo ">7</option>
		<option value=\"56\"";if ($default_jump_around_5 == "56") { echo " selected=\"selected\""; } echo ">8</option>
		<option value=\"57\"";if ($default_jump_around_5 == "57") { echo " selected=\"selected\""; } echo ">9</option>
		<option value=\"37\"";if ($default_jump_around_5 == "37") { echo " selected=\"selected\""; } echo ">left arrow</option>
		<option value=\"38\"";if ($default_jump_around_5 == "38") { echo " selected=\"selected\""; } echo ">up arrow</option>
		<option value=\"39\"";if ($default_jump_around_5 == "39") { echo " selected=\"selected\""; } echo ">right arrow</option>
		<option value=\"40\"";if ($default_jump_around_5 == "40") { echo " selected=\"selected\""; } echo ">down arrow</option>
		</select>
		</label>
		<br />
		
		<label for=\"jump_around_6\">Next key:
		<select name=\"jump_around_6\">
		<option value=\"65\"";if ($default_jump_around_6 == "65") { echo " selected=\"selected\""; } echo ">a</option>
		<option value=\"66\"";if ($default_jump_around_6 == "66") { echo " selected=\"selected\""; } echo ">b</option>
		<option value=\"67\"";if ($default_jump_around_6 == "67") { echo " selected=\"selected\""; } echo ">c</option>
		<option value=\"68\"";if ($default_jump_around_6 == "68") { echo " selected=\"selected\""; } echo ">d</option>
		<option value=\"69\"";if ($default_jump_around_6 == "69") { echo " selected=\"selected\""; } echo ">e</option>
		<option value=\"70\"";if ($default_jump_around_6 == "70") { echo " selected=\"selected\""; } echo ">f</option>
		<option value=\"71\"";if ($default_jump_around_6 == "71") { echo " selected=\"selected\""; } echo ">g</option>
		<option value=\"72\"";if ($default_jump_around_6 == "72") { echo " selected=\"selected\""; } echo ">h</option>
		<option value=\"73\"";if ($default_jump_around_6 == "73") { echo " selected=\"selected\""; } echo ">i</option>
		<option value=\"74\"";if ($default_jump_around_6 == "74") { echo " selected=\"selected\""; } echo ">j</option>
		<option value=\"75\"";if ($default_jump_around_6 == "75") { echo " selected=\"selected\""; } echo ">k</option>
		<option value=\"76\"";if ($default_jump_around_6 == "76") { echo " selected=\"selected\""; } echo ">l</option>
		<option value=\"77\"";if ($default_jump_around_6 == "77") { echo " selected=\"selected\""; } echo ">m</option>
		<option value=\"78\"";if ($default_jump_around_6 == "78") { echo " selected=\"selected\""; } echo ">n</option>
		<option value=\"79\"";if ($default_jump_around_6 == "79") { echo " selected=\"selected\""; } echo ">o</option>
		<option value=\"80\"";if ($default_jump_around_6 == "80") { echo " selected=\"selected\""; } echo ">p</option>
		<option value=\"81\"";if ($default_jump_around_6 == "81") { echo " selected=\"selected\""; } echo ">q</option>
		<option value=\"82\"";if ($default_jump_around_6 == "82") { echo " selected=\"selected\""; } echo ">r</option>
		<option value=\"83\"";if ($default_jump_around_6 == "83") { echo " selected=\"selected\""; } echo ">s</option>
		<option value=\"84\"";if ($default_jump_around_6 == "84") { echo " selected=\"selected\""; } echo ">t</option>
		<option value=\"85\"";if ($default_jump_around_6 == "85") { echo " selected=\"selected\""; } echo ">u</option>
		<option value=\"86\"";if ($default_jump_around_6 == "86") { echo " selected=\"selected\""; } echo ">v</option>
		<option value=\"87\"";if ($default_jump_around_6 == "87") { echo " selected=\"selected\""; } echo ">w</option>
		<option value=\"88\"";if ($default_jump_around_6 == "88") { echo " selected=\"selected\""; } echo ">x</option>
		<option value=\"89\"";if ($default_jump_around_6 == "89") { echo " selected=\"selected\""; } echo ">y</option>
		<option value=\"90\"";if ($default_jump_around_6 == "90") { echo " selected=\"selected\""; } echo ">z</option>
		<option value=\"48\"";if ($default_jump_around_6 == "48") { echo " selected=\"selected\""; } echo ">0</option>
		<option value=\"49\"";if ($default_jump_around_6 == "49") { echo " selected=\"selected\""; } echo ">1</option>
		<option value=\"50\"";if ($default_jump_around_6 == "50") { echo " selected=\"selected\""; } echo ">2</option>
		<option value=\"51\"";if ($default_jump_around_6 == "51") { echo " selected=\"selected\""; } echo ">3</option>
		<option value=\"52\"";if ($default_jump_around_6 == "52") { echo " selected=\"selected\""; } echo ">4</option>
		<option value=\"53\"";if ($default_jump_around_6 == "53") { echo " selected=\"selected\""; } echo ">5</option>
		<option value=\"54\"";if ($default_jump_around_6 == "54") { echo " selected=\"selected\""; } echo ">6</option>
		<option value=\"55\"";if ($default_jump_around_6 == "55") { echo " selected=\"selected\""; } echo ">7</option>
		<option value=\"56\"";if ($default_jump_around_6 == "56") { echo " selected=\"selected\""; } echo ">8</option>
		<option value=\"57\"";if ($default_jump_around_6 == "57") { echo " selected=\"selected\""; } echo ">9</option>
		<option value=\"37\"";if ($default_jump_around_6 == "37") { echo " selected=\"selected\""; } echo ">left arrow</option>
		<option value=\"38\"";if ($default_jump_around_6 == "38") { echo " selected=\"selected\""; } echo ">up arrow</option>
		<option value=\"39\"";if ($default_jump_around_6 == "39") { echo " selected=\"selected\""; } echo ">right arrow</option>
		<option value=\"40\"";if ($default_jump_around_6 == "40") { echo " selected=\"selected\""; } echo ">down arrow</option>
		</select>		
		</label>
		<br />
		
		<label for=\"jump_around_7\">Older posts key:
		<select name=\"jump_around_7\">
		<option value=\"65\"";if ($default_jump_around_7 == "65") { echo " selected=\"selected\""; } echo ">a</option>
		<option value=\"66\"";if ($default_jump_around_7 == "66") { echo " selected=\"selected\""; } echo ">b</option>
		<option value=\"67\"";if ($default_jump_around_7 == "67") { echo " selected=\"selected\""; } echo ">c</option>
		<option value=\"68\"";if ($default_jump_around_7 == "68") { echo " selected=\"selected\""; } echo ">d</option>
		<option value=\"69\"";if ($default_jump_around_7 == "69") { echo " selected=\"selected\""; } echo ">e</option>
		<option value=\"70\"";if ($default_jump_around_7 == "70") { echo " selected=\"selected\""; } echo ">f</option>
		<option value=\"71\"";if ($default_jump_around_7 == "71") { echo " selected=\"selected\""; } echo ">g</option>
		<option value=\"72\"";if ($default_jump_around_7 == "72") { echo " selected=\"selected\""; } echo ">h</option>
		<option value=\"73\"";if ($default_jump_around_7 == "73") { echo " selected=\"selected\""; } echo ">i</option>
		<option value=\"74\"";if ($default_jump_around_7 == "74") { echo " selected=\"selected\""; } echo ">j</option>
		<option value=\"75\"";if ($default_jump_around_7 == "75") { echo " selected=\"selected\""; } echo ">k</option>
		<option value=\"76\"";if ($default_jump_around_7 == "76") { echo " selected=\"selected\""; } echo ">l</option>
		<option value=\"77\"";if ($default_jump_around_7 == "77") { echo " selected=\"selected\""; } echo ">m</option>
		<option value=\"78\"";if ($default_jump_around_7 == "78") { echo " selected=\"selected\""; } echo ">n</option>
		<option value=\"79\"";if ($default_jump_around_7 == "79") { echo " selected=\"selected\""; } echo ">o</option>
		<option value=\"80\"";if ($default_jump_around_7 == "80") { echo " selected=\"selected\""; } echo ">p</option>
		<option value=\"81\"";if ($default_jump_around_7 == "81") { echo " selected=\"selected\""; } echo ">q</option>
		<option value=\"82\"";if ($default_jump_around_7 == "82") { echo " selected=\"selected\""; } echo ">r</option>
		<option value=\"83\"";if ($default_jump_around_7 == "83") { echo " selected=\"selected\""; } echo ">s</option>
		<option value=\"84\"";if ($default_jump_around_7 == "84") { echo " selected=\"selected\""; } echo ">t</option>
		<option value=\"85\"";if ($default_jump_around_7 == "85") { echo " selected=\"selected\""; } echo ">u</option>
		<option value=\"86\"";if ($default_jump_around_7 == "86") { echo " selected=\"selected\""; } echo ">v</option>
		<option value=\"87\"";if ($default_jump_around_7 == "87") { echo " selected=\"selected\""; } echo ">w</option>
		<option value=\"88\"";if ($default_jump_around_7 == "88") { echo " selected=\"selected\""; } echo ">x</option>
		<option value=\"89\"";if ($default_jump_around_7 == "89") { echo " selected=\"selected\""; } echo ">y</option>
		<option value=\"90\"";if ($default_jump_around_7 == "90") { echo " selected=\"selected\""; } echo ">z</option>
		<option value=\"48\"";if ($default_jump_around_7 == "48") { echo " selected=\"selected\""; } echo ">0</option>
		<option value=\"49\"";if ($default_jump_around_7 == "49") { echo " selected=\"selected\""; } echo ">1</option>
		<option value=\"50\"";if ($default_jump_around_7 == "50") { echo " selected=\"selected\""; } echo ">2</option>
		<option value=\"51\"";if ($default_jump_around_7 == "51") { echo " selected=\"selected\""; } echo ">3</option>
		<option value=\"52\"";if ($default_jump_around_7 == "52") { echo " selected=\"selected\""; } echo ">4</option>
		<option value=\"53\"";if ($default_jump_around_7 == "53") { echo " selected=\"selected\""; } echo ">5</option>
		<option value=\"54\"";if ($default_jump_around_7 == "54") { echo " selected=\"selected\""; } echo ">6</option>
		<option value=\"55\"";if ($default_jump_around_7 == "55") { echo " selected=\"selected\""; } echo ">7</option>
		<option value=\"56\"";if ($default_jump_around_7 == "56") { echo " selected=\"selected\""; } echo ">8</option>
		<option value=\"57\"";if ($default_jump_around_7 == "57") { echo " selected=\"selected\""; } echo ">9</option>
		<option value=\"37\"";if ($default_jump_around_7 == "37") { echo " selected=\"selected\""; } echo ">left arrow</option>
		<option value=\"38\"";if ($default_jump_around_7 == "38") { echo " selected=\"selected\""; } echo ">up arrow</option>
		<option value=\"39\"";if ($default_jump_around_7 == "39") { echo " selected=\"selected\""; } echo ">right arrow</option>
		<option value=\"40\"";if ($default_jump_around_7 == "40") { echo " selected=\"selected\""; } echo ">down arrow</option>
		</select>		
		</label>
		<br />

		<label for=\"jump_around_8\">Newer posts key:
		<select name=\"jump_around_8\">
		<option value=\"65\"";if ($default_jump_around_8 == "65") { echo " selected=\"selected\""; } echo ">a</option>
		<option value=\"66\"";if ($default_jump_around_8 == "66") { echo " selected=\"selected\""; } echo ">b</option>
		<option value=\"67\"";if ($default_jump_around_8 == "67") { echo " selected=\"selected\""; } echo ">c</option>
		<option value=\"68\"";if ($default_jump_around_8 == "68") { echo " selected=\"selected\""; } echo ">d</option>
		<option value=\"69\"";if ($default_jump_around_8 == "69") { echo " selected=\"selected\""; } echo ">e</option>
		<option value=\"70\"";if ($default_jump_around_8 == "70") { echo " selected=\"selected\""; } echo ">f</option>
		<option value=\"71\"";if ($default_jump_around_8 == "71") { echo " selected=\"selected\""; } echo ">g</option>
		<option value=\"72\"";if ($default_jump_around_8 == "72") { echo " selected=\"selected\""; } echo ">h</option>
		<option value=\"73\"";if ($default_jump_around_8 == "73") { echo " selected=\"selected\""; } echo ">i</option>
		<option value=\"74\"";if ($default_jump_around_8 == "74") { echo " selected=\"selected\""; } echo ">j</option>
		<option value=\"75\"";if ($default_jump_around_8 == "75") { echo " selected=\"selected\""; } echo ">k</option>
		<option value=\"76\"";if ($default_jump_around_8 == "76") { echo " selected=\"selected\""; } echo ">l</option>
		<option value=\"77\"";if ($default_jump_around_8 == "77") { echo " selected=\"selected\""; } echo ">m</option>
		<option value=\"78\"";if ($default_jump_around_8 == "78") { echo " selected=\"selected\""; } echo ">n</option>
		<option value=\"79\"";if ($default_jump_around_8 == "79") { echo " selected=\"selected\""; } echo ">o</option>
		<option value=\"80\"";if ($default_jump_around_8 == "80") { echo " selected=\"selected\""; } echo ">p</option>
		<option value=\"81\"";if ($default_jump_around_8 == "81") { echo " selected=\"selected\""; } echo ">q</option>
		<option value=\"82\"";if ($default_jump_around_8 == "82") { echo " selected=\"selected\""; } echo ">r</option>
		<option value=\"83\"";if ($default_jump_around_8 == "83") { echo " selected=\"selected\""; } echo ">s</option>
		<option value=\"84\"";if ($default_jump_around_8 == "84") { echo " selected=\"selected\""; } echo ">t</option>
		<option value=\"85\"";if ($default_jump_around_8 == "85") { echo " selected=\"selected\""; } echo ">u</option>
		<option value=\"86\"";if ($default_jump_around_8 == "86") { echo " selected=\"selected\""; } echo ">v</option>
		<option value=\"87\"";if ($default_jump_around_8 == "87") { echo " selected=\"selected\""; } echo ">w</option>
		<option value=\"88\"";if ($default_jump_around_8 == "88") { echo " selected=\"selected\""; } echo ">x</option>
		<option value=\"89\"";if ($default_jump_around_8 == "89") { echo " selected=\"selected\""; } echo ">y</option>
		<option value=\"90\"";if ($default_jump_around_8 == "90") { echo " selected=\"selected\""; } echo ">z</option>
		<option value=\"48\"";if ($default_jump_around_8 == "48") { echo " selected=\"selected\""; } echo ">0</option>
		<option value=\"49\"";if ($default_jump_around_8 == "49") { echo " selected=\"selected\""; } echo ">1</option>
		<option value=\"50\"";if ($default_jump_around_8 == "50") { echo " selected=\"selected\""; } echo ">2</option>
		<option value=\"51\"";if ($default_jump_around_8 == "51") { echo " selected=\"selected\""; } echo ">3</option>
		<option value=\"52\"";if ($default_jump_around_8 == "52") { echo " selected=\"selected\""; } echo ">4</option>
		<option value=\"53\"";if ($default_jump_around_8 == "53") { echo " selected=\"selected\""; } echo ">5</option>
		<option value=\"54\"";if ($default_jump_around_8 == "54") { echo " selected=\"selected\""; } echo ">6</option>
		<option value=\"55\"";if ($default_jump_around_8 == "55") { echo " selected=\"selected\""; } echo ">7</option>
		<option value=\"56\"";if ($default_jump_around_8 == "56") { echo " selected=\"selected\""; } echo ">8</option>
		<option value=\"57\"";if ($default_jump_around_8 == "57") { echo " selected=\"selected\""; } echo ">9</option>
		<option value=\"37\"";if ($default_jump_around_8 == "37") { echo " selected=\"selected\""; } echo ">left arrow</option>
		<option value=\"38\"";if ($default_jump_around_8 == "38") { echo " selected=\"selected\""; } echo ">up arrow</option>
		<option value=\"39\"";if ($default_jump_around_8 == "39") { echo " selected=\"selected\""; } echo ">right arrow</option>
		<option value=\"40\"";if ($default_jump_around_8 == "40") { echo " selected=\"selected\""; } echo ">down arrow</option>
		</select>
		</label>
		<br /><hr />
		
		<label for=\"jump_around_delete_on_deactivate\">Delete options on deactivation?:
		<select name=\"jump_around_delete_on_deactivate\">
			<option value=\"yes\""; if ($default_jump_around_delete_on_deactivate == "yes") { echo " selected=\"selected\""; } echo ">Yes</option>
			<option value=\"no\""; if ($default_jump_around_delete_on_deactivate == "no") { echo " selected=\"selected\""; } echo ">No</option>
		</select>

		
		</label><br />
		

		<input type=\"submit\" name=\"submit\" value=\"Save\" />
		</form>";
	}
	
// JA-003
// The options page
	add_action("admin_menu", "JA_add_options_page");
	
	function JA_add_options_page() {
		add_options_page("JA", "Jump Around", "manage_options", "JA", "JA_page_content");
	}

// Display the information on the page that was created.
	function JA_page_content() { 
		echo "	
				<div id=\"ja\">
				<h2>Jump Around</h2>
				<p>Navigate posts by pressing keys on the keyboard.<br />
				<p>
				Default keys:<br />
				<span class=\"keys\"><span class=\"left\">a</span><span class=\"right\">previous</span></span>
				<span class=\"keys\"><span class=\"left\">d</span><span class=\"right\">next</span></span>
				<span class=\"keys\"><span class=\"left\">s</span><span class=\"right\">open currently selected post</span></span>
				<span class=\"keys\"><span class=\"left\">z</span><span class=\"right\">older posts</span></span>
				<span class=\"keys\"><span class=\"left\">x</span><span class=\"right\">newer posts</span></span>
				</p>
				
				<blockquote>
					<p>Adds a class of .current to the currently selected item (which you can style in .css).</p>
					<p>Custom keys must not be the same.  Each one must be different.</p>
				
				<blockquote>
					";
					if ($_REQUEST["submit"]) { update_JA(); }
					print_JA_form();
				echo "</blockquote>
				
				<p>
				Thanks to <a href=\"http://stackoverflow.com/questions/1939041/change-hash-without-reload-in-jquery\">jitter</a> &amp; <a href=\"http://stackoverflow.com/questions/13694277/scroll-to-next-div-using-arrow-keys\">mVChr</a><br /><br />
				</p>
				
				</blockquote>
				</div>";
	}
	
	
// JA-004
// Output appropriate script in footer

function Jump_Around_footer_script(){
if (is_archive() || is_home()) { 
$JA_0_sc = ( get_option("jump_around_0") );
$JA_1_sc = ( get_option("jump_around_1") );
$JA_2_sc = ( get_option("jump_around_2") );
$JA_3_sc = ( get_option("jump_around_3") );
$JA_4_sc = ( get_option("jump_around_4") );
$JA_5_sc = ( get_option("jump_around_5") );
$JA_6_sc = ( get_option("jump_around_6") );
$JA_7_sc = ( get_option("jump_around_7") );
$JA_8_sc = ( get_option("jump_around_8") );
echo "
<script type=\"text/javascript\">
jQuery( document ).ready( function($) {
var hash = window.location.hash.substr(1);
if(hash != false && hash != 'undefined') {
    \$('#'+hash+'').addClass('current');
	\$(document).keydown(function(e){
    switch(e.which) {
        case ",$JA_4_sc,":
            var \$current = \$('.",$JA_0_sc,".current'),
            \$prev_embed = \$current.prev();
            \$('html, body').animate({scrollTop:\$prev_embed.offset().top - 100}, 500);
            \$current.removeClass('current');
            \$prev_embed.addClass('current');
			window.location.hash = \$('.",$JA_0_sc,".current').attr('id');
			e.preventDefault();
            return;
        break;
		case ",$JA_6_sc,": 
            var \$current = \$('.",$JA_0_sc,".current'),
            \$next_embed = \$current.next('.",$JA_0_sc,"');
            \$('html, body').animate({scrollTop:\$next_embed.offset().top - 100}, 500);
            \$current.removeClass('current');
            \$next_embed.addClass('current');
			window.location.hash = \$('.",$JA_0_sc,".current').attr('id');
			e.preventDefault();
            return;
        break;
		case ",$JA_5_sc,": 
                if(jQuery('.current .",$JA_1_sc," a').attr('href'))
                document.location.href=jQuery('.current .",$JA_1_sc," a').attr('href');
				e.preventDefault();
				return;
				break;
        default: return; 
    }
    
});
}else{
\$('.",$JA_0_sc,":eq(0)').addClass('current');
\$(document).keydown(function(e){
    switch(e.which) {
        case ",$JA_4_sc,": 
            var \$current = \$('.",$JA_0_sc,".current'),
            \$prev_embed = \$current.prev();
            \$('html, body').animate({scrollTop:\$prev_embed.offset().top - 100}, 500);
            \$current.removeClass('current');
            \$prev_embed.addClass('current');
			window.location.hash = \$('.",$JA_0_sc,".current').attr('id');
			e.preventDefault();
            return;
        break;
		case ",$JA_6_sc,": 
            var \$current = \$('.",$JA_0_sc,".current'),
            \$next_embed = \$current.next('.",$JA_0_sc,"');
            \$('html, body').animate({scrollTop:\$next_embed.offset().top - 100}, 500);
            \$current.removeClass('current');
            \$next_embed.addClass('current');
			window.location.hash = \$('.",$JA_0_sc,".current').attr('id');
			e.preventDefault();
            return;
        break;
		case ",$JA_5_sc,": 
                if(jQuery('.current .",$JA_1_sc," a').attr('href'))
                document.location.href=jQuery('.current .",$JA_1_sc," a').attr('href');
				e.preventDefault();
				return;
				break;
	}
    
});
}

if (\$('.",$JA_2_sc," a').is('*')) {
\$(document).keydown(function(e){
    switch(e.which) {
        case ",$JA_7_sc,": 
			document.location.href=jQuery('.",$JA_2_sc," a').attr('href');
			e.preventDefault();
            return;
			break;
    }
    
});
}

if (\$('.",$JA_3_sc," a').is('*')) {
\$(document).keydown(function(e){
    switch(e.which) {
		case ",$JA_8_sc,": 
			document.location.href=jQuery('.",$JA_3_sc," a').attr('href');
			e.preventDefault();
            return;
			break;
    }
    
});
}

});
</script>
";
}
} 

add_action('wp_footer', 'Jump_Around_footer_script');
	
?>