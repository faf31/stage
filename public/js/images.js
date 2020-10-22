window.onload =()=>{
    //gestion des bouton supprimer
    let links = document.querySelectorAll("[data-delete]")
    console.log(links)
    // on boucle sur links
    for(link of links){
link.addEventListener("click", function(e){
    e.preventDefault()
    if(confirm("voulez vous vraiment supprimer cette image ?")){
        fetch(this.getAttribute("href"),{
            method:"DELETE",
            headers: {
                "X-Requested-With": "XLMHttpRequest",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({"_token":this.dataset.token})
        }).then(
            response=>response.json()
        ).then(data=>{
            if(data.success)
            this.parentElement.remove()
            else
            alert(data.error)
        }).catch(e=>alert(e))

    }

})
    }
}