/*

	Copyright Mackan <thormax5@gmail.com>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

if(document.getElementById("noti-toggle") != null){


	if(JSON.parse($("#notifs").val()).length > 0){
		var data = JSON.parse($("#notifs").val());

		var str = "";

		for(var i=0;i<data.length;i++){
			str += "<span class='notif'>"+data[i]['body']+"</span><br>";
		}

	}else{
		str = "<span class='notif'>No new notifications</span>";
	}

	$('#noti-toggle').tooltipster({
		content: $(str)
	});

}