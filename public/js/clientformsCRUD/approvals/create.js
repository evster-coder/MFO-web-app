document.addEventListener("DOMContentLoaded", function(){
	var acceptForm = document.getElementById('accept');
	var rejectForm = document.getElementById('reject');

	acceptForm.addEventListener("submit", function(e){
		acceptForm.comment.value = document.getElementById('commentField').value
		return true;
	}, false);

	rejectForm.addEventListener("submit", function(e){
		rejectForm.comment.value = document.getElementById('commentField').value
		return true;
	}, false);
});