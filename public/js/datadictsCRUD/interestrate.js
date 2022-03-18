  function validate()
  {
    if(document.dataForm.percent_value.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }
