import React, { useEffect } from "react";
import { sendPlaylist } from "../api";
import arrow from "../assets/arrow.svg";


interface GetMusic{
    UseValue:any,
}

class SpotifyPage extends React.Component<GetMusic>{

    state= {
        tab:[],
        name:"",
    }

    componentDidUpdate(prevstate:any){
        prevstate=this.state.tab;
        if(prevstate !== this.props.UseValue){
            this.setState({tab: this.props.UseValue});
            prevstate = this.state.tab;
        }
    }
    
    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const { name } = this.state;
        const music = this.props.UseValue;
        try {

            // this.setState({musiquesList:[]});
            const playlist = await sendPlaylist({ name, music });
            // this.setState({musiquesList: login});

        } catch (error) {
            // alert(error);
        }
    }

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ name: e.currentTarget.value});
    }
    
    render() {
        
        return(
            <div className="bloc1">
                <div className="header">
                    <h1 className="playlistTitle">Nouvelle playlist</h1>
                </div>
                <form
                    onSubmit={this.handleSubmit}
                    className="playlistForm"
                >
                    <input
                        name="playlistName"
                        type="text"
                        className="playlistName"
                        placeholder="Nom de votre playlist"
                        onChange={this.onChange}
                    >
                    </input>
                    <button 
                        name="Create Playlist"
                        type="submit"
                        className="buttonPlaylist"
                        value="Valider"
                    >
                        <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.668 7C12.802 7 9.66797 3.86599 9.66797 4.17233e-07" stroke="black" stroke-width="0.8"/>
                            <path d="M9.66797 14C9.66797 10.134 12.802 7 16.668 7" stroke="black" stroke-width="0.8"/>
                            <path d="M16.6667 7L0 7" stroke="black" stroke-width="0.8"/>
                        </svg>
                        Créer la playlist
                    </button>

                </form>
                <div className="scroll1">
                    {
                        Object.keys(this.props.UseValue["idTab"] ? this.props.UseValue["idTab"]:"").map((key) => 
                            <div className="music">
                                {
                                    this.props.UseValue["idTab"][key]["title"]
                                }
                            </div>
                        )    
                    }
                </div>
        </div>

        )
    }

}

export default SpotifyPage;