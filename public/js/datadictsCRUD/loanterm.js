  function validate()
  {
    if(document.dataForm.daysAmount.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }
