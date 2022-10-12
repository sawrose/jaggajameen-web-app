jQuery(document).ready(function (){
    fetchClient();
});
function fetchClient(){
    $.ajax({
        type: "GET",
        url: "/viewFirebaseUser",
        dataType: "json",
        success: function(response){
            i = 1;
            $.each(response.ref, function(key, item){
                var clientTBody = '<tr class="tableRow">\
                                <td>'+i+'</td>\
                                <td>'+item.email+'</td>\
                                <td>'+item.metadata.createdAt.slice(0,10)+'</td>\
                                <td>'+item.metadata.lastLoginAt.slice(0,10)+'</td>\
                                <td class="actionCol">\
                                    <button style="" type="submit" id="delBtn" class="delBtn viewUser" value="'+item.uid+'">Delete</button>\
                                </td>\
                            </tr>';
                    i++;
                    $('#OpTable #opTableTBody').append(clientTBody);
            });            
            $('#OpTable').DataTable();
        }
    });
}

//Delete Client
$(document).on('click', '.delBtn', function (e){
    e.preventDefault();
    var cid = $(this).val();
    console.log(cid);

    var flag;
    if (confirm("Are you sure?")) {
        $.ajax({
            type: "GET",
            url: "/destroy/"+cid,
            success: function(response){
                $('#usersTableTBody').html("");
                if(response.status == 200){
                    alert(response.message);
                    fetchOperators();
                }else{
                    alert(response.errors);
                }
            }
        });
    } else {
        alert('Operation aborted.');
    }    
});