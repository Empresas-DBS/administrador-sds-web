var cronInput = document.getElementById("cronInput");
var cronStringPositionJson = {
  minute: {
    start: null,
    end: null
  },
  hour: {
    start: null,
    end: null
  },
  dayOfMonth: {
    start: null,
    end: null
  },
  month: {
    start: null,
    end: null
  },
  dayOfWeek: {
    start: null,
    end: null
  }
};

// For Initial Load update the UI with default cronstring and show "Minute" options by default.
updateCronFragments(cronInput.value);
showHighlightedCronFragmentOptions(0,cronStringPositionJson);

$('#cronInput').on('click keyup blur change paste', function(e) {
//  $("#warning").hide();
//$('#cronInput').on('keyup paste', function(e) {
  //if (cronInput.value && cronInput.value.trim().split(" ").length === 5)  
  {
    resetLinkColours();
    $("#cronInput").removeClass("is-invalid");
    $("#iemdiv").text("Expresión de cron válida");
    $("#iemdiv").addClass("text-success");
    // document.getElementById("copycron").disabled = true;
    var cursorStartPosition = cronInput.selectionStart;
    updateCronFragments(cronInput.value);
    showHighlightedCronFragmentOptions(cursorStartPosition, cronStringPositionJson);
    var errorFragment = validateCron(cronInput.value);
    if (errorFragment) 
    {
      toggleandHighlight(errorFragment, true);
      $("#cronInput").addClass("is-invalid");
      $("#iemdiv").text("Expresión de cron inválida, corregir "+whichBlock(errorFragment));
      $("#iemdiv").removeClass("text-success");
      document.getElementById("cronsTrueExpression").innerHTML = "";
      $(".btn-new-cron").prop("disabled", true);
    } 
    else if (cronInput.value.trim().split(" ").length < 5) 
    {
      $("#cronInput").addClass("is-invalid");
      $("#iemdiv").text("Expresión de cron válida debe tener 5 partes");
      $("#iemdiv").removeClass("text-success");
      document.getElementById("cronsTrueExpression").innerHTML = "";
      $(".btn-new-cron").prop("disabled", true);
    } 
    else if (cronInput.value.trim().split(" ").length > 5) 
    {
      $("#cronInput").addClass("is-invalid");
      $("#iemdiv").text("Expresión de cron válida debe tener solo 5 partes");
      $("#iemdiv").removeClass("text-success");
      document.getElementById("cronsTrueExpression").innerHTML = "";
      $(".btn-new-cron").prop("disabled", true);
    } 
    else 
    {
        document.getElementById("cronsTrueExpression").innerHTML = cronstrue.toString(cronInput.value);
        if($("#scriptPath").val() != "")
          $(".btn-new-cron").prop("disabled", false);
          
        // document.getElementById("copycron").disabled = false;
    }
  } 
  /*else {
    //if (cronInput.value.trim().split(" ").length > 5) 
    {
    // $("#warning").show();
    //} else {
      updateCronFragments(cronInput.value);
      showHighlightedCronFragmentOptions(cursorStartPosition, cronStringPositionJson);
      $("#cronInput").addClass("is-invalid");
      $("#iemdiv").text("Valid cron expression should have 5 parts");
      errorLinkColours();
    }
  }*/
});

function toggleCronFragmentOptions(fragmentOptionId) {
  $(".cron-fragment-options").hide();
  $(fragmentOptionId).show();
}

function toggleandHighlight(id, isError) {
  resetLinkColours();
  toggleCronFragmentOptions(id);
  if(!isError) {
    if (id === "#minuteOptions") {
      setCaretPosition(cronInput, cronStringPositionJson.minute.start, cronStringPositionJson.minute.end);
    } else if (id === "#hourOptions") {
      setCaretPosition(cronInput, cronStringPositionJson.hour.start, cronStringPositionJson.hour.end);
    } else if (id === "#dayOfMonthOptions") {
      setCaretPosition(cronInput, cronStringPositionJson.dayOfMonth.start, cronStringPositionJson.dayOfMonth.end);
    } else if (id === "#monthOptions") {
      setCaretPosition(cronInput, cronStringPositionJson.month.start, cronStringPositionJson.month.end);
    } else if (id === "#dayOfWeekOptions") {
      setCaretPosition(cronInput, cronStringPositionJson.dayOfWeek.start, cronStringPositionJson.dayOfWeek.end);
    }
  } else {
    $(id+"link").removeClass("text-success");
    $(id+"link").addClass("text-danger");
    $("#cronInput").addClass("is-invalid");
    $("#iemdiv").text("Invalid Cron Expression, correct "+whichBlock(id));
  }
}

function reset() {
  cronInput.value = "* * * * *";
  document.getElementById("cronsTrueExpression").innerHTML = cronstrue.toString(cronInput.value);
  updateCronFragments(cronInput.value);
  showHighlightedCronFragmentOptions(0,cronStringPositionJson);
  resetLinkColours();
  $("#warning").hide();
  cronInput.value = "";
}

function copy() {
  copyToClipboard(cronInput.value);
  showToast("success", i18n_copyclipboardMessage); //No i18N
}

function updateCronFragments(cronString) {
  try {
    document.getElementById("cronsTrueExpression").innerHTML = cronstrue.toString(cronString);  
  } catch(e) {}
  
  // var cronSplitString = cronString.split(" "); // Use this if you need to get input vales of each cron fragment
  var spaceIndices = [];
  for (var i = 0; i < cronString.length; i++) {
    if (cronString[i] === " ") {
      spaceIndices.push(i);
    }
  }
  cronStringPositionJson.minute.start = 0;
  cronStringPositionJson.minute.end = spaceIndices[0];

  cronStringPositionJson.hour.start = spaceIndices[0] + 1;
  cronStringPositionJson.hour.end = spaceIndices[1];

  cronStringPositionJson.dayOfMonth.start = spaceIndices[1] + 1;
  cronStringPositionJson.dayOfMonth.end = spaceIndices[2];

  cronStringPositionJson.month.start = spaceIndices[2] + 1;
  cronStringPositionJson.month.end = spaceIndices[3];

  cronStringPositionJson.dayOfWeek.start = spaceIndices[3] + 1;
  cronStringPositionJson.dayOfWeek.end = cronString.length;
}

function showHighlightedCronFragmentOptions(cursorStartPosition, cronPositionJson) {
  if (
    cursorStartPosition >= cronPositionJson.minute.start &&
    cursorStartPosition <= cronPositionJson.minute.end
  ) {
    toggleCronFragmentOptions("#minuteOptions");
  } else if (
    cursorStartPosition >= cronPositionJson.hour.start &&
    cursorStartPosition <= cronPositionJson.hour.end
  ) {
    toggleCronFragmentOptions("#hourOptions");
  } else if (
    cursorStartPosition >= cronPositionJson.dayOfMonth.start &&
    cursorStartPosition <= cronPositionJson.dayOfMonth.end
  ) {
    toggleCronFragmentOptions("#dayOfMonthOptions");
  } else if (
    cursorStartPosition >= cronPositionJson.month.start &&
    cursorStartPosition <= cronPositionJson.month.end
  ) {
    toggleCronFragmentOptions("#monthOptions");
  } else {
    toggleCronFragmentOptions("#dayOfWeekOptions");
  }
}

function setCaretPosition(ctrl, start, end) {
  // Modern browsers
  if (ctrl.setSelectionRange) {
    ctrl.focus();
    ctrl.setSelectionRange(start, end);
  
  // IE8 and below
  } else if (ctrl.createTextRange) {
    var range = ctrl.createTextRange();
    range.collapse(true);
    range.moveEnd('character', pos);
    range.moveStart('character', pos);
    range.select();
  }
}

function resetLinkColours() {
  $("#minuteOptionslink").addClass("text-success");
  $("#minuteOptionslink").removeClass("text-danger");
  $("#hourOptionslink").addClass("text-success");
  $("#hourOptionslink").removeClass("text-danger");
  $("#dayOfMonthOptionslink").addClass("text-success");
  $("#dayOfMonthOptionslink").removeClass("text-danger");
  $("#monthOptionslink").addClass("text-success");
  $("#monthOptionslink").removeClass("text-danger");
  $("#dayOfWeekOptionslink").addClass("text-success");
  $("#dayOfWeekOptionslink").removeClass("text-danger");
  $("#cronInput").removeClass("is-invalid");
  $("#iemdiv").text("Invalid Cron Expression");
}

function errorLinkColours() {
  $("#minuteOptionslink").addClass("text-danger");
  $("#hourOptionslink").addClass("text-danger");
  $("#dayOfMonthOptionslink").addClass("text-danger");
  $("#monthOptionslink").addClass("text-danger");
  $("#dayOfWeekOptionslink").addClass("text-danger");
}

//CRON Validation logic starts -  Dont modify this code
function validateCron(inputString) {
  var cronArray = inputString.split(" ");
  let minutes = cronArray[0];
  let hours = cronArray[1];
  let dayOfMonth = cronArray[2];
  let month = cronArray[3];
  let dayOfWeek = cronArray[4];
  //let isValidMinutes = validateTime(minutes, 59);
  if(!validateTime("minutes", minutes, 0, 59)) {
    return "#minuteOptions";
  }
  if(!validateTime("hours", hours, 0, 23)) {
    return "#hourOptions";
  }
  if(!validateTime("dayOfMonth", dayOfMonth, 1, 31)) {
    return "#dayOfMonthOptions";
  }
  if(!validateTime("month", month, 1, 12)) {
    return "#monthOptions";
  }
  if(!validateTime("dayOfWeek", dayOfWeek, 0, 6)) {
    return "#dayOfWeekOptions";
  }
}

function whichBlock(errorFragment) {
  if (errorFragment === '#minuteOptions') {
    return "minutes part";
  } else if (errorFragment === '#hourOptions') {
    return "hour part";
  } else if (errorFragment === '#dayOfMonthOptions') {
    return "day(month) part";
  } else if (errorFragment === '#monthOptions') {
    return "month part";
  } else if (errorFragment === '#dayOfWeekOptions') {
    return "day(week) part";
  } 
}

function validateTime(fragment, value, minTimeUnit, maxTimeUnit) {
 //var ex = "1-4/5,15,16-23,34/35";
 if (!value) {
  return false;
 } else if (value) {
    value = value.trim();
    if(!value) {
      return false;
    }
 }
 
 if (fragment === 'dayOfWeek' && value.indexOf(",") === -1) {
  if ((value === 'L' || value === '?')) {
    return true;
  }
  if (value.indexOf("L") > -1) {
    if(value.startsWith("L") || value.endsWith("L")) {
      value = value.replace("L", "");
      if (value) {
        value = value.trim();
        if (value) {
          return isValueInt(value, minTimeUnit, maxTimeUnit);
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  if (value.indexOf("#") > -1) {
     if(value.startsWith("#") || value.endsWith("#")) {
      return false;
     } else {
      var nthArr = value.split("#");
      if(nthArr && nthArr.length === 2) {
        if(!isValueInt(nthArr[0], 0, 7)) {
          return false;
        }
        return isValueInt(nthArr[1], 1, 5);
      } else {
        return false;
      }
     }
  }
 }
 if (fragment === 'dayOfMonth' && value.indexOf(",") === -1) {
  if ((value === 'L' || value === 'LW' || value === 'WL' || value === '?')) {
    return true;
  }
  if (value.indexOf("W") > -1) {
    if(value.startsWith("W") || value.endsWith("W")) {
      value = value.replace("W", "");
      if (value) {
        value = value.trim();
        if (value) {
          return isValueInt(value, minTimeUnit, maxTimeUnit);
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  if (value.indexOf("L") > -1) {
    if(value.startsWith("L-") || value.endsWith("-L")) {
      value = value.replace("L-", "");
      value = value.replace("-L", "");
      value = value.trim();
      if (value) {
        return isValueInt(value, minTimeUnit, maxTimeUnit);
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
 }
 if (value === "*") {
  return true;
 } else if (value === "/" || value === "-" || value === ",") {
  return false;
 /*} else if(!new RegExp("^[0-9,-/*]*$").test(value)) {
  return false;*/
 } else {
    if (value.indexOf(",") > -1) {
      var values = value.trim().split(",");
      if (values && values.length > 0) {
        for (var i = 0; i < values.length; i++) {
           //validateIndices(values[i], maxTimeUnit)
           if(!validateTime(fragment, values[i], minTimeUnit, maxTimeUnit)) {
            return false;
           }
        }
      } else {
        return false;
      }
    } else if (value.indexOf("/") > -1) {
      return isStepValid(value, minTimeUnit, maxTimeUnit);
    } else if (value.indexOf("-") > -1) {
      return isRangeValid(value, minTimeUnit, maxTimeUnit)
    } else {
      //validateIndices(value, maxTimeUnit);
      return isValueInt(value, minTimeUnit, maxTimeUnit);
    }
 }
 
 return true;
}

function isStepValid(indices, minTimeUnit, maxTimeUnit) {
  var steps = indices.split("/");
  if (steps && steps.length !== 2) {
    return false;
  } else {
    if (steps[0] !== '*' ) {

      if (steps[0].indexOf("*") > -1 || (steps[0].indexOf("-") > -1 && !isRangeValid(steps[0], minTimeUnit, maxTimeUnit))) {
        return false;
      } else if (steps[0] !== '*' && steps[0].indexOf("-") === -1 && (!steps[0] || !isValueInt(steps[0], minTimeUnit, maxTimeUnit))) {
        return false;
      } 
    }
    

    if (!steps[1] || steps[1] === '*' || steps[1].indexOf('*') > -1 || steps[1] === '-' || steps[1].indexOf('-') > -1 || steps[1] === ',' || steps[1].indexOf(',') > -1) {
      return false;
    }
    if(!isValueInt(steps[1], minTimeUnit, maxTimeUnit)) {
      return false;
    }
  }

  return true;
}

function isRangeValid(ranges, minTimeUnit, maxTimeUnit) {
  var rangeArr = ranges.split("-");
  if (rangeArr && rangeArr.length !== 2) {
    return false;
  } else {
    if (rangeArr[0] === '*' || !rangeArr[0] || !isValueInt(rangeArr[0], minTimeUnit, maxTimeUnit)) {
      return false;
    }
    if (!rangeArr[1] || !isValueInt(rangeArr[1], minTimeUnit, maxTimeUnit)) {
      return false;
    }
    try {
      if (parseInt(rangeArr[0]) >= parseInt(rangeArr[1])) {
        return false;
      }
    } catch (e) {

    }
   
  }

  return true;
}

function isValueInt(value, minTimeUnit, maxTimeUnit) {
  const months = ["january", "jan", "february", "feb", "march", "mar", "april", "apr", "may", "june", "jun", "july", "jul", "august", "aug", "september", "sep", "october", "oct", "november", "nov", "december", "dec"];
  const days = ["sunday", "sun", "monday", "mon", "tuesday", "tue", "wednesday", "wed", "thursday", "thu", "friday", "fri", "saturday", "sat"];
  try {
    /*if (maxTimeUnit === 12) {
      if (months.indexOf(value.toLowerCase()) > -1) {
        return true;
      }
    }*/
    if (maxTimeUnit === 12 && months.indexOf(value.toLowerCase()) > -1) {
      return true;
    }
    if (maxTimeUnit === 6 && days.indexOf(value.toLowerCase()) > -1) {
      return true;
    }
    if(!value || !new RegExp("^[0-9]*$").test(value)) {
    //if (!value || Number(value) === 'NaN') {
      return false;
    } 
    if (value && (parseInt(value) < minTimeUnit || parseInt(value) > maxTimeUnit)) {
      return false;
    }
  } catch (e) {
    return false;
  }

  return true;
}
//CRON Validation logic ends

// function signUpAndMonitor() {
//   window.location.href = "/signup.html?utm_source=tools-promo&pack=44&l=" + languageCode;
// }