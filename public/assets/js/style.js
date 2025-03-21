 
let users = [
    { id: 1, name: "Jean  ", email: "jean@example.com" },
     
];

document.addEventListener("DOMContentLoaded", loadUsers);

function loadUsers() {
    const usersList = document.getElementById("usersList");
    usersList.innerHTML = "";
    users.forEach(user => {
        usersList.innerHTML += `
            <tr class="text-left">
                <td class="border border-gray-300 px-4 py-2">${user.id}</td>
                <td class="border border-gray-300 px-4 py-2">${user.name}</td>
                <td class="border border-gray-300 px-4 py-2">${user.email}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <a href="#" class="text-green-600 mx-2"><i class="fa fa-eye"></i></a>
                    <a href="#" class="text-blue-600 mx-2"><i class="fa fa-edit"></i></a>
                    <a href="#" onclick="deleteUser(${user.id})" class="text-red-600 mx-2"><i class="fa fa-trash"></i></a>
                </td>
            </tr>`; 
    });
}

function addUser(event) {
    event.preventDefault();
    let name = document.getElementById("userName").value;
    let email = document.getElementById("userEmail").value;
    users.push({ id: users.length + 1, name, email });
    loadUsers();
    toggleModal(false);
}

function deleteUser(id) {
    users = users.filter(user => user.id !== id);
    loadUsers();
}

function toggleModal(show) {
    document.getElementById('addUserModal').classList.toggle('hidden', !show);
}
 
