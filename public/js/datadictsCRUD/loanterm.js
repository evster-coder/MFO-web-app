  function validate()
  {
    if(document.dataForm.days_amount.value !='')
      document.dataForm.btnsave.disabled=false
    else
      document.dataForm.btnsave.disabled=true
  }
