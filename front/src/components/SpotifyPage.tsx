import React, { useEffect } from "react";
import { sendPlaylist } from "../api";
import search from "../assets/search.svg";


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
        const {music} = this.props.UseValue;
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
                <div>
                    {
                        Object.keys(this.props.UseValue["idTab"] ? this.props.UseValue["idTab"]:"").map((key) => 
                            <div>
                                {
                                    this.props.UseValue["idTab"][key]["title"]
                                }
                            </div>
                        )    
                    }
                </div>
                <form
                    onSubmit={this.handleSubmit}
                    className="osuForm"
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
                        className="button"
                        value="Valider"
                    >
                    </button>

                </form>
        </div>

        )
    }

}

export default SpotifyPage;