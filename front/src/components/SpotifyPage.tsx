import React, { useEffect } from "react";

interface GetMusic{
    UseValue:any,
    // Exists: boolean;
    // setExists: (value: boolean) => void;
}

class SpotifyPage extends React.Component<GetMusic>{

    state= {
        tab:[],
    }

    componentDidUpdate(prevstate:any){
        prevstate=this.state.tab;
        if(prevstate !== this.props.UseValue){
            this.setState({tab: this.props.UseValue});
            prevstate = this.state.tab;
        }
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

        </div>

        )
    }

}

export default SpotifyPage;