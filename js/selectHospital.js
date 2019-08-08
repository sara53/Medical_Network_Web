		function onSelectHospital() {

			var form = document.getElementById("form_data");
			
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", "hospital_select");
			hiddenField.setAttribute("value", document.getElementById("hospital").value);
			form.appendChild(hiddenField);
			
			form.submit();
		}