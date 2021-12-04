import React, { useState, useEffect } from "react";
import { Route, Router } from "react-router-dom";
import Banner from "./Banner";
import SearchOsu from "./SearchOsu";
import SpotifyPage from "./SpotifyPage";

interface AppContextInterface {
    musicTab:any,
}

export const AppCtx = React.createContext<AppContextInterface | null>(null);
const sampleAppContext: AppContextInterface = {
    musicTab:["pull_data(tab)"],
};

const PlaylistPage = () => {
    const [tab, setTab] = useState([""]);
    const [ok, setOk] = useState(true);
console.log(ok);

    const pull_data = (data:any) => {
        console.log(data);
        
        if(ok){
            data['idTab'][0] = data["idTab"][1];
            data['idTab'].splice(0,1);
            setOk(false);
        }
        let interTab:Array<string> = [];
        console.log(data['idTab']);
        
        for(let i=1;i<=data.length; i++){
            interTab.push(data[i]);
            // console.log("lol");
            // console.log(interTab);
        }
        setTab(interTab);
        return(interTab);
    }

    return(
        <div className="App">
            <Banner />
            <AppCtx.Provider value={sampleAppContext}>
                <SpotifyPage UseValue={tab}/>
            </AppCtx.Provider>
            <SearchOsu onSuccess={pull_data}/>
        </div>
    )
}

export default PlaylistPage;