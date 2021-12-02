import React from "react";
import Banner from "./Banner";
import OsuPage from "./OsuPage";
import SpotifyPage from "./SpotifyPage";

class PlaylistPage extends React.Component{
    state = {
        token:""
    }

    componentDidMount(){
        const queryParams = new URLSearchParams(window.location.search);
        const token  = queryParams.get('token');
        localStorage.setItem('userToken',token == null ? "token":token);
        console.log(localStorage.getItem('userToken'));
    }
    
    render() {
        return(
            <div className="App">
                <Banner />
                <SpotifyPage />
                <OsuPage />
            </div>
        )
    }
}

export default PlaylistPage;