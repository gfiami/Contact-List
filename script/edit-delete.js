function editContact(element){
   document.querySelector('.insert').hidden = true;
   document.querySelector('.delete').hidden = true;
   document.querySelector('.edit').hidden = false;
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
    document.querySelector('.contact-image-file-edit').src = `./contact-images/${photoEdit}`

    document.querySelector('.idEdit').value = idEdited
   }

function deleteContact(element){
    document.querySelector('.insert').hidden = true;
   document.querySelector('.edit').hidden = true;
    document.querySelector('.delete').hidden = false;
    let nameDelete = element.getAttribute('data-name')
    let emailDelete = element.getAttribute('data-email')
    let phoneDelete = element.getAttribute('data-phone');
    let photoDelete = element.getAttribute('data-photo');
    let idDelete = element.getAttribute('data-id');
    document.querySelector('#nameDelete').value = nameDelete
    document.querySelector('#emailDelete').value = emailDelete
    document.querySelector('#phoneDelete').value = phoneDelete
    document.querySelector('#contact-image-file-delete').value = photoDelete
    document.querySelector('.contact-image-file-delete').src = `./contact-images/${photoDelete}`
    document.querySelector('.idDelete').value = idDelete
    //
}
const imageEdit = document.querySelector('.image-edit');
const keepImage = document.querySelector('#keepImage')
const changeImage = document.querySelector('#changeImage')
const imageInput = document.querySelector('#contact-image-edit')
imageInput.disabled = true;
keepImage.addEventListener('click', ()=> {
    imageInput.disabled = true;
    imageEdit.style.opacity = "1"
})
changeImage.addEventListener('click', ()=>{
    imageInput.disabled = false;
    imageEdit.style.opacity = "0.4";
})