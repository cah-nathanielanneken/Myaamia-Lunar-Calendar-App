  function getNextMonth(date) {
    $.get("getMonth.php?date="+date+"&direction=next", function( data ) {
      $("#calendar > div").remove();
      $("#calendar").append(data);
    });
  }

  function getPreviousMonth(date) {
    $.get("getMonth.php?date="+date+"&direction=previous", function( data ) {
      $("#calendar > div").remove();
      $("#calendar").append(data);
    });
  } 
