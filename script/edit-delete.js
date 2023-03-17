function editContact(element){
    let inputInsert = document.querySelector('.insert').hidden = true;
    let inputDelete = document.querySelector('.delete').hidden = true;
    let inputEdit = document.querySelector('.edit').hidden = false;
    let nameEdit = element.getAttribute('data-name')
    let emailEdit = element.getAttribute('data-email')
    let phoneEdit = element.getAttribute('data-phone')
    let birthEdit = element.getAttribute('data-birth');
    let photoEdit = element.getAttribute('data-photo');
    let idEdited = element.getAttribute('data-id');
    document.querySelector('#nameEdit').value = nameEdit
    document.querySelector('#emailEdit').value = emailEdit
    document.querySelector('#phoneEdit').value = phoneEdit
    document.querySelector('#birthdateEdit').value = birthEdit
    document.querySelector('#contact-image-file-edit').value = photoEdit
    document.querySelector('.idEdit').value = idEdited
   }

function deleteContact(element){
    let inputInsert = document.querySelector('.insert').hidden = true;
    let inputEdit = document.querySelector('.edit').hidden = true;
    let inputDelete = document.querySelector('.delete').hidden = false;
    let nameDelete = element.getAttribute('data-name')
    let emailDelete = element.getAttribute('data-email')
    let phoneDelete = element.getAttribute('data-phone');
    let photoDelete = element.getAttribute('data-photo');
    let idDelete = element.getAttribute('data-id');
    console.log(idDelete);
    document.querySelector('#nameDelete').value = nameDelete
    document.querySelector('#emailDelete').value = emailDelete
    document.querySelector('#phoneDelete').value = phoneDelete
    document.querySelector('#contact-image-file-delete').value = photoDelete
    document.querySelector('.contact-image-file-delete').src = `./contact-images/${photoDelete}`
    document.querySelector('.idDelete').value = idDelete
    //
}