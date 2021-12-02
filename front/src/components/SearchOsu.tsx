import React from "react";
import { getOsuPseudo } from "../api";
import { OsuUser } from "../decl/osuUser.decl";
import OsuPage from "./OsuPage";

export type State = {
    pseudo: string,
    musiquesList: string[],
};

class SearchOsu extends React.Component{
    state = {
        pseudo: "",
        musiquesList: [],
    }

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ pseudo: e.currentTarget.value});
    }

    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const { pseudo } = this.state;

        try {
            
            const login = await getOsuPseudo({ pseudo });
            this.setState({musiquesList: login});

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
                        this.state.musiquesList.map(item => (
                            <div key={item['titre']}>
                                {item['titre']}
                            </div>
                        ))
                    }
                </div>
            </div>
        )
    }

}

export default SearchOsu;  
