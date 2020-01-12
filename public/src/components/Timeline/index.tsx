'use strict';

import * as React from 'react';
import { TimelineTweet } from "../TimelineTweet";
import { Tweet } from '@app/types/Tweet';

interface Props {}

interface State {
  error?: Error,
  isLoaded: boolean,
  tweets: Array<Tweet>
};

export class Timeline extends React.Component<Props, State> {
  state: State = {
    isLoaded: false,
    tweets: []
  }

  public componentDidMount(): void {
    fetch("/twitter")
      .then(res => res.json())
      .then(
        (tweets) => {
          this.setState({
            isLoaded: true,
            tweets: tweets
          });
        },
        // Note: it's important to handle errors here
        // instead of a catch() block so that we don't swallow
        // exceptions from actual bugs in components.
        (error) => {
          this.setState({
            isLoaded: true,
            error
          });
        }
      )
  }

  public render() {
    const { error, isLoaded } = this.state;

    if (error) {
      return <div>Error: {error.message}</div>;
    } else if (!isLoaded) {
      return <div>Loading...</div>;
    } else {
        return this.renderTweets(this.state.tweets);
    }
  }

  private renderTweets(tweets: Array<Tweet>) {
    return tweets.map(((tweet) => (
        <TimelineTweet screenName={tweet.screen_name} imageUrl={tweet.uri} text={tweet.text} />
      ))
    );
  }
}
