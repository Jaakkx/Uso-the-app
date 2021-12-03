import React, { useState, useEffect } from "react";
import { Route, Router } from "react-router-dom";
import Banner from "./Banner";
import OsuPage from "./OsuPage";
import SpotifyPage from "./SpotifyPage";

function PlaylistPage (){

    const [token, setToken] = useState(0);
    const [isStock, setStock] = useState(1);

    useEffect(() => {

        console.log(localStorage.getItem('userToken'));

  
    })
    
    return(
        <div className="App">
            <Banner />
            <SpotifyPage />
            <OsuPage />
        </div>
    )
}

export default PlaylistPage;