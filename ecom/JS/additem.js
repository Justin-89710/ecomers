// document.addEventListener('DOMContentLoaded', function() {
//     // Function to add a new file input
//     function addFileInput() {
//         var fileInputCount = document.querySelectorAll('.form-group input[type="file"]').length + 1;
//
//         if (fileInputCount <= 4) {
//             var newInput = document.createElement('input');
//             newInput.type = 'file';
//             newInput.name = 'img' + fileInputCount;
//             newInput.id = 'fileToUpload' + fileInputCount;
//             newInput.className = 'form-control';
//             newInput.required = false;
//
//             var label = document.createElement('label');
//             label.textContent = 'Upload photo ' + fileInputCount + ' (optional)';
//
//             var formGroup = document.createElement('div');
//             formGroup.className = 'form-group';
//             formGroup.appendChild(label);
//             formGroup.appendChild(newInput);
//
//             document.querySelector('.imgup').appendChild(formGroup);
//         }
//     }
//
//     // Event listener for file inputs
//     document.addEventListener('change', function(event) {
//         if (event.target && event.target.type === 'file') {
//             if (event.target.value.trim() !== '') {
//                 addFileInput();
//             }
//         }
//     });
// });