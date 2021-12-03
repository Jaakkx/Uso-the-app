import React from "react";


function GetToken(){
    const queryParams = new URLSearchParams(window.location.search);
    const token  = queryParams.get('token');
    localStorage.setItem('userToken',token == null ? "error":token);
    window.location.href = "/playlist-creator";

    return(
        <div className="display-none">
            It's working
        </div>
    );

}

export default GetToken;