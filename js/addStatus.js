	function addStatus(){
	
		var currentTime = new Date();
		var day = currentTime.getDate();
		var month = currentTime.getMonth()+1;
		var year = currentTime.getFullYear();
		var hours = currentTime.getHours();
		var minutes = currentTime.getMinutes();

		var monthStr;
		switch(month){
			case 1:
				monthStr = "JAN";
				break;
			case 2:
				monthStr = "FEB";
				break;
			case 3:
				monthStr = "MAR";
				break;
			case 4:
				monthStr = "APR";
				break;
			case 5:
				monthStr = "MAY";
				break;
			case 6:
				monthStr = "JUN";
				break;
			case 7:
				monthStr = "JUL";
				break;
			case 8:
				monthStr = "AUG";
				break;
			case 9:
				monthStr = "SEP";
				break;
			case 10:
				monthStr = "OCT";
				break;
			case 11:
				monthStr = "NOV";
				break;
			case 12:
				monthStr = "DEC";
				break;
		}

		if (hours < 10) {
			hours = "0" + hours;
		}

		if (minutes < 10) {
			minutes = "0" + minutes;
		}
		
		var dateTime = day + " " + monthStr + " " + year + " at " + hours + ":" + minutes;
		
		if(document.getElementById("overall").value == "" || document.getElementById("details").value == ""){
			document.getElementById("error_txt").innerHTML = "Please fill out all fields!";
		}
		
		else {
			var form = document.getElementById("form_add");
			var dateTime_element = document.createElement("input");
			dateTime_element.setAttribute("type", "hidden");
			dateTime_element.setAttribute("name", "date_and_time");
			dateTime_element.setAttribute("value", dateTime);
			form.appendChild(dateTime_element);
				
			form.submit();
		}
	}