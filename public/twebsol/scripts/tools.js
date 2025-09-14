function downloadTextFile(content, fileName = "textfile.txt") {
  // Create a Blob with the file content
  var blob = new Blob([content], { type: "text/plain" });

  // Create a link element
  var link = document.createElement("a");

  // Set the link's properties
  link.href = window.URL.createObjectURL(blob);
  link.download = fileName;

  // Append the link to the document
  document.body.appendChild(link);

  // Trigger a click on the link to start the download
  link.click();

  // Remove the link from the document
  document.body.removeChild(link);
}

function downloadImage(container, fileName = "image.png") {
  //! Requires Html2Canvas

  // Get the div element to capture
  var element = document.querySelector(container);

  // Use HTML2Canvas to capture the content as a canvas
  html2canvas(element).then(function (canvas) {
    // Convert the canvas to a data URL
    var dataURL = canvas.toDataURL("image/png");

    // Create a download link
    var link = document.createElement("a");
    link.href = dataURL;
    link.download = `${fileName}.png`;

    // Append the link to the document and trigger a click
    document.body.appendChild(link);
    link.click();

    // Remove the link from the document
    document.body.removeChild(link);
  });
}
