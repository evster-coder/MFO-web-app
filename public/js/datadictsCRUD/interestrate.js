  function validate()
  {
    if(document.dataForm.percentValue.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }
