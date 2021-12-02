import React from "react";
import { getOsuPseudo } from "../api";
import { OsuUser } from "../decl/osuUser.decl";
import OsuPage from "./OsuPage";

export type State = {
    pseudo: string,
    musiques: string[],
};

class SearchOsu extends React.Component{
    state = {
        pseudo: "",
        musiques: []
    }

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ pseudo: e.currentTarget.value});
    }

    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const { pseudo } = this.state;

        try {
            const login = await getOsuPseudo({ pseudo });
            this.setState({musiques: login});
        } catch (error) {
            alert(error);
        }
    }

    render() {
        return(
            <div>
                <form
                    onSubmit={this.handleSubmit}
                >
                    <input
                        name="osuPseudo"
                        type="text"
                        onChange={this.onChange}
                    >
                    </input>
                    <input type="submit" value="Submit"></input>
                </form>

                <div>
                    {
                        this.state.musiques.map(item => (
                            <div key={item}>
                                {item}
                            </div>
                        ))
                    }
                </div>
            </div>
        )
    }

}

export default SearchOsu;  
