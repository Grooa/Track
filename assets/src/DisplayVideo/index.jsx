import React from 'react';
import PropTypes from 'prop-types';

import ModuleApi from './moduleApi';
import VideoPlayer from './VideoPlayer';
import VideoDescription from './VideoDescription';
import VideoResources from './VideoResources';

export default class DisplayVideo extends React.Component {
  constructor() {
    super();

    this.state = {
      loaded: false,
      module: {},
    };

    this.hasLoaded = this.hasLoaded.bind(this);
    this.hasModule = this.hasModule.bind(this);
    this.hasResources = this.hasResources.bind(this);
    this.getCurrentVideo = this.getCurrentVideo.bind(this);
  }

  componentDidMount() {
    ModuleApi.findById(this.props.moduleId)
      .then(data => this.setState({ loaded: true, module: data }))
      .catch((err) => {
        this.setState({ loaded: true });
        console.error(err);
      });
  }

  hasLoaded() {
    return this.state.loaded;
  }

  hasModule() {
    return !!this.state.module && Object.keys(this.state.module).length > 0;
  }

  hasResources() {
    if (!this.hasModule() || this.state.module.videos.length < 1) {
      return false;
    }

    const video = this.getCurrentVideo();

    return Object.keys(video).length > 0 && video.resources.length > 0;
  }

  getCurrentVideo() {
    return this.state.module.videos.find(v => v.id === this.props.videoId);
  }

  render() {
    if (!this.hasLoaded()) {
      return <div>
        Loading...
      </div>;
    }

    const video = this.getCurrentVideo();

    if (!video) {
      return <div className="feedback feedback-error">
        <p>Cannot find video for module: {this.state.module.title}</p>
      </div>;
    }

    const { url = null } = video;
    const { cover } = this.state.module;

    return <article className="video-page">

      <div className="video-player-frame">
        <VideoPlayer
          url={url}
          poster={cover} />

        <section className="video-titles">
          <h1>{this.hasModule() && this.state.module.title}</h1>
        </section>
      </div>

      <div className="video-metadata columns">
        <VideoDescription
          className="course-information most-important"
          description={this.hasModule() ? this.state.module.longDescription : null} />

        <VideoResources
          className="course-information"
          resources={(this.hasModule() && this.hasResources()) ? video.resources : null}/>
      </div>

    </article>;
  }
}

DisplayVideo.propTypes = {
  moduleId: PropTypes.number.isRequired,
  videoId: PropTypes.number.isRequired,
};
