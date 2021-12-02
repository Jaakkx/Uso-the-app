import React from "react";
import { getOsuPseudo } from "../api";
import { OsuUser } from "../decl/osuUser.decl";
import SearchOsu from "./SearchOsu";

export type OsuPageState = {
    musiques:[],
}

class OsuPage extends React.Component{
    state = {
        musiques: [],
    }

    updateState(newValue:[]){
        this.setState({
            musiques: newValue,
        })
    }

    render() {
        return(
            <SearchOsu />
        )
    }

}

export default OsuPage;