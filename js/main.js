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
$('document').ready(function(){

	$('#btn-login').on('click', function(){
		$('#login').submit();
	});

	$('#btn-reg').on('click', function(e){
		e.preventDefault();

		var toP = {"name":$('#name2').val(),"pass":$('#pass2').val(),"email":$("#email").val()};
		console.log(toP);
		$.ajax({
		  type: "POST",
		  url: "./register.php",
		  data: toP,
		  success: function(data){
		  	console.log(data);
		  	if(data == "Success!"){
		  		swal("Success!", "You're now registered!", "success");
		  	}else{
		  		swal("Error!", data, "error");
		  	}
		  }
		});

	});

	$("#reg-form").on("submit", function(e){
		e.preventDefault();
		$("#btn-reg").click();
	});

	$("img").on("error", function(){
		$(this).attr("src", "img/broken.jpg");
	});
});