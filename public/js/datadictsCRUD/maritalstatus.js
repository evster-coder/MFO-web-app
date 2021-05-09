  function validate()
  {
    if(document.dataForm.name.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }