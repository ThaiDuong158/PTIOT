(function () {
  // Drag and Drop functionality
  function dropHandler(event) {
    event.preventDefault();
    event.stopPropagation();

    let items = event.dataTransfer.items;
    if (items.length > 0) {
      handleItems(items);
    }
  }

  function dragOverHandler(event) {
    event.preventDefault();
    event.stopPropagation();
    event.dataTransfer.dropEffect = 'copy';
  }

  function dragEnterHandler(event) {
    event.preventDefault();
    event.stopPropagation();
    document.getElementById('drop_zone').classList.add('dragover');
  }

  function dragLeaveHandler(event) {
    event.preventDefault();
    event.stopPropagation();
    document.getElementById('drop_zone').classList.remove('dragover');
  }

  function handleItems(items) {
    let fileList = document.getElementById('file_list');
    let dropZoneText = document.getElementById('drop_zone_text');
    dropZoneText.style.display = 'none'; // Hide the default text

    for (let i = 0; i < items.length; i++) {
      let item = items[i].webkitGetAsEntry();
      if (item) {
        traverseFileTree(item);
      }
    }
  }

  function traverseFileTree(item, path = "") {
    if (item.isFile) {
      item.file(function (file) {
        displayFile(path + file.name);
      });
    } else if (item.isDirectory) {
      let dirReader = item.createReader();
      dirReader.readEntries(function (entries) {
        for (let i = 0; i < entries.length; i++) {
          traverseFileTree(entries[i], path + item.name + "/");
        }
      });
    }
  }

  function displayFile(fileName) {
    let fileList = document.getElementById('file_list');
    let listItem = document.createElement('p');
    listItem.textContent = fileName;
    fileList.appendChild(listItem);
  }

  // Handle file selection from input
  function handleFileSelect(event) {
    console.log(event.target.files)
    let files = event.target.files;
    let fileList = document.getElementById('file_list');
    let dropZoneText = document.getElementById('drop_zone_text');
    dropZoneText.style.display = 'none'; // Ẩn văn bản mặc định

    // Xóa bỏ các tệp cũ trong danh sách
    // fileList.innerHTML = '';

    // Thêm các tệp mới vào danh sách
    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        displayFile(file.name);
    }

  }

  // Add event listeners for drag and drop
  let dropZone = document.getElementById('drop_zone');
  dropZone.addEventListener('drop', dropHandler);
  dropZone.addEventListener('dragover', dragOverHandler);
  dropZone.addEventListener('dragenter', dragEnterHandler);
  dropZone.addEventListener('dragleave', dragLeaveHandler);

  // Handle file input button click to trigger file input
  let fileInputButton = document.getElementById('file_input_button');
  let fileInput = document.getElementById('file_input');
  fileInputButton.addEventListener('click', function () {
    fileInput.click();
  });

  // Handle file input change event
  fileInput.addEventListener('change', handleFileSelect);

})();
