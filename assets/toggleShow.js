function toggleWarning() {
    var x = document.getElementById("deleteButton");
    var w = document.getElementById("warningMsg");
    if (x.style.display === "none") {
      x.style.display = "block";
      w.style.display = "none";
      document.getElementById('submit_delete').type="submit";
    } else {
      x.style.display = "none";
      w.style.display = "block";
      document.getElementById('submit_delete').type="hidden";
    }
  }