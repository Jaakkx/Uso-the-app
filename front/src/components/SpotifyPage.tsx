import React, { useEffect } from "react";
import {AppCtx} from "./PlaylistPage";

interface GetMusic{
    UseValue:any,
    // Exists: boolean;
    // setExists: (value: boolean) => void;
}

// function SpotifyPage(){
//     const appContext = React.useContext(AppCtx);

//     useEffect(() => {
//         console.log("ok");
//     })

//     console.log(appContext?.musicTab);

//     return(
//         <div className="bloc1">
//         <div className="header">
//             <h1 className="playlistTitle">Nouvelle playlist</h1>
//         </div>
//         <div>
//             Test : {appContext?.musicTab}
//         </div>

//         </div>
// )
// }

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
        // console.log(this.state.tab);
    }
    
    
    render() {

        // console.log(JSON.parse(localStorage.getItem('idTab') ?? ""))
        return(
        <div className="bloc1">
        <div className="header">
            <h1 className="playlistTitle">Nouvelle playlist</h1>
        </div>
        <div>
            Test : {
                // this.state.tab['']

            }
        </div>

        </div>

        )
    }

}

export default SpotifyPage;