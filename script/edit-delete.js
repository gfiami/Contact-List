function editContact(element){
    let inputInsert = document.querySelector('.insert').hidden = true;
    let inputEdit = document.querySelector('.edit').hidden = false;
    let nameEdit = element.getAttribute('data-name')
    let emailEdit = element.getAttribute('data-email')
    let phoneEdit = element.getAttribute('data-phone')
    let birthEdit = element.getAttribute('data-birth');
    let idEdited = element.getAttribute('data-id');
    console.log(idEdited);
    document.querySelector('#nameEdit').value = nameEdit
    document.querySelector('#emailEdit').value = emailEdit
    document.querySelector('#phoneEdit').value = phoneEdit
    document.querySelector('#birthdateEdit').value = birthEdit
    document.querySelector('.idEdit').value = idEdited
    
   }

function deleteContact(element){
    let nomeParaDeletar = element.getAttribute('data-nome')
    let emailParaDeletar = element.getAttribute('data-email')
    let idDeletar = element.getAttribute('data-id');
    document.querySelector('.insert-data').style.display = 'none';
    document.querySelector('#editar').style.display = 'none'
    document.querySelector('#deletar').style.display = 'block'
    document.querySelector('.nomeDelete').value = nomeParaDeletar;
    document.querySelector('.emailDelete').value = emailParaDeletar;
    document.querySelector('.idDelete').value = idDeletar;
    document.querySelector('.deleteMsg').innerHTML = `Remover usuário "${nomeParaDeletar}", com email "${emailParaDeletar}"?`
}