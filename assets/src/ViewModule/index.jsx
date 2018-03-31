import React from 'react';
import PropTypes from 'prop-types';
import ReactMarkdown from 'react-markdown';

import ModuleApi from '../DisplayVideo/moduleApi';
import FeedbackSection from '../common/FeedbackSection';
import VideoList from './VideoList';

import { fetchConfigProps } from '../common/config';

export default class ViewModule extends React.Component {
  constructor() {
    super();

    this.state = {
      loaded: false,
      module: {},
    };

    this.hasLoaded = this.hasLoaded.bind(this);
    this.hasModule = this.hasModule.bind(this);
    this.hasVideos = this.hasVideos.bind(this);
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
    return Object.keys(this.state.module).length > 0;
  }

  hasVideos() {
    return this.state.module.videos.length > 0;
  }

  render() {
    if (!this.hasLoaded()) {
      return <FeedbackSection
        type="loading"
        text="Loading" />;
    }

    if (!this.hasModule()) {
      return <FeedbackSection
        type="error"
        text="No such module for this course" />;
    }

    // TODO:ffl - The course-metadata is almost identical to ViewCourse,
    // TODO:ffl - consider converting it to a pure component
    return <article className="course-module-page">
      <header className="title-area">
        <strong className="type">{this.state.module.type}</strong>
        <h1 className="title">{this.state.module.title}</h1>
      </header>

      <FeedbackSection
        type="must-login"
        title="Login to access"
        text="You will need to be logged into you account, before accessing this module"
        link={{ url: `${fetchConfigProps().baseUrl}login`, label: 'Login to user' }} />

      <div className="course-metadata columns float-top spaced">
        <section className="modules-section no-fill">
          <h2 className="clean">Videos</h2>
          {this.hasVideos()
            ? <VideoList videos={this.state.module.videos} />
            : <FeedbackSection text="No videos has been published yet" />}
        </section>

        <section className="course-description">
          <h2 className="clean">About this module</h2>

          <ReactMarkdown className="data" source={this.state.module.longDescription} />
        </section>
      </div>
    </article>;
  }
}

ViewModule.propTypes = {
  moduleId: PropTypes.number.isRequired,
};
